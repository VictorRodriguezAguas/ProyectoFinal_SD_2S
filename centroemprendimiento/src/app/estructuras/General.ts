import { Respuesta } from './respuesta';
import { formatDate } from '@angular/common';
import Swal from 'sweetalert2';

export class General {

  static generateId(len?) {
    var arr = new Uint8Array((len || 40) / 2)
    window.crypto.getRandomValues(arr)
    return Array.from(arr, this.dec2hex).join('')
  }

  private static dec2hex(dec) {
    return ('0' + dec.toString(16)).substr(-2)
  }

  static respuesta: Respuesta = {
    codigo: "0",
    mensaje: "Ha ocurrido un error inesperado",
    data: null,
    mensaje_error: "Ha ocurrido un error inesperado",
  };

  public static getValidElementForm(element, isSubmit: boolean): boolean {
    if (!element.disabled) {
      return !element.valid && (element.dirty || element.touched || isSubmit)
    }
    return false;
  }

  public static getValidElementFormExcluyente(element, isSubmit: boolean, disabled?): boolean {
    if (disabled) {
      element.disable();
    } else {
      element.enable();
    }
    if (!element.disabled) {
      return !element.valid && (element.dirty || element.touched || isSubmit);
    }
    return false;
  }

  public static getFechaActual() {
    let dateObj = new Date();
    let yearMonth = dateObj.getUTCFullYear() + '-' + (dateObj.getUTCMonth() + 1) + '-' + dateObj.getUTCDate();
    let fechaActual = formatDate(yearMonth, 'yyyy-MM-dd', 'en-US');
    return fechaActual;
  }

  public static getFechaActualHora() {
    let dateObj = new Date();
    let yearMonth = dateObj.getUTCFullYear() + '-' + (dateObj.getUTCMonth() + 1) + '-' + dateObj.getUTCDate() + " " + dateObj.getHours() + ':' + dateObj.getUTCMinutes() + ":" + dateObj.getUTCSeconds();
    let fechaActual = formatDate(yearMonth, 'yyyy-MM-dd HH:mm:ss', 'en-US');
    return fechaActual;
  }

  public static getDataOptionAlert(lista) {
    let data = {};
    lista.forEach(item => {
      data[item.id] = item.nombre;
    });
    return data;
  }

  public static loading() {
    Swal.fire({
      title: 'Cargando...',
      html: '',
      allowOutsideClick: false,
      customClass:{
        popup: 'loadingAlert'
      },
      onBeforeOpen: () => {
        Swal.showLoading()
      }
    });
  }

  public static closeLoading() {
    Swal.close();
  }

  public static getFoto(url_foto){
    if(url_foto)
      return url_foto;
    return 'images/user.png';
  }

  public static pictNotLoading(event) { event.target.src = 'images/user.png'; }

  public static getEdad(fecha) {
    var hoy = new Date();
    
    if(!fecha){
      return null;
    }

    var array_fecha = fecha.split("-")
    //si el array no tiene tres partes, la fecha es incorrecta
    if (array_fecha.length !== 3)
        return null;
    //compruebo que los ano, mes, dia son correctos
    var ano = parseInt(array_fecha[0]);
    if (isNaN(ano))
        return null;

    var mes = parseInt(array_fecha[1]);
    if (isNaN(mes))
        return null;

    var dia = parseInt(array_fecha[2]);
    if (isNaN(dia))
        return null;

    if (ano <= 99)
        ano += 1900;
    //resto los años de las dos fechas
    var edad = hoy.getFullYear() - ano - 1; //-1 porque no se si ha cumplido años ya este año

    //si resto los meses y me da menor que 0 entonces no ha cumplido años. Si da mayor si ha cumplido
    //if (hoy.getMonth() + 1 - mes < 0) //+ 1 porque los meses empiezan en 0
    //    return edad
    if (hoy.getMonth() + 1 - mes > 0)
        edad += 1;

    //entonces es que eran iguales. miro los dias
    //si resto los dias y me da menor que 0 entonces no ha cumplido años. Si da mayor o igual si ha cumplido
    if (hoy.getUTCDate() - dia >= 0)
        edad += 1;
    
    return edad;
}
}
