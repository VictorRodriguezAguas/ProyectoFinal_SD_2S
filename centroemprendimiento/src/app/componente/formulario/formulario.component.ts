import { Component, Input, OnInit, Output, EventEmitter, SimpleChanges, OnChanges } from '@angular/core';
import { Usuario } from 'src/app/estructuras/usuario';
import { Campo, Formulario } from 'src/app/interfaces/Formulario';
import { FormularioService } from 'src/app/servicio/Formulario.service';
import { GeneralService } from 'src/app/servicio/mantenimiento/General.service';
import { MensajeService } from 'src/app/servicio/Mensaje.service';

@Component({
  selector: 'app-formulario',
  templateUrl: './formulario.component.html',
  styleUrls: ['./formulario.component.css']
})
export class FormularioComponent implements OnInit, OnChanges {

  @Input() id_formulario;
  @Input() id_registro;
  @Input() editable = true;
  @Input() submit = false;

  ngOnChanges(changes: SimpleChanges) {
    if(changes.submit){
      if (changes.submit.currentValue && this.editable) {
        this.grabarFormulario();
      }
    }
  }

  @Input() clasColum = "col-md-6";

  @Output() getFormulario = new EventEmitter<any>();

  formulario: Formulario;
  formularioValue: Formulario;

  usuario: Usuario = Usuario.getUser();

  constructor(private formularioService: FormularioService,
    private generalService: GeneralService,
    private mensajeService: MensajeService) { }

  ngOnInit() {
    this.formularioService.getFormulario(this.id_formulario).subscribe(data => {
      if (data.codigo == '1') {
        this.formulario = data.data
        this.formulario.id_registro_formulario = null;
        this.formulario.id_usuario_registro = this.usuario.id_usuario;
        this.formulario.campos.forEach(campo => {
          campo.valor = null;
          campo.valorAux = null;
          campo.otro_valor = null;
          campo.submit = false;
          campo.valido = true;
          if (campo.obligatorio == 'SI') {
            campo.valido = false;
          }
          this.getLista(campo);
        });
        this.indexarListas();
      } else {
        this.mensajeService.alertError(null, data.mensaje);
      }
    });

    if (this.id_registro) {
      this.formularioService.getRegistro(this.id_registro).subscribe(data => {
        console.log('Registro formulario');
        console.log(data);
        if (data.codigo == '1') {
          this.formularioValue = data.data;
          this.indexarListas();
        } else {
          this.mensajeService.alertError(null, data.mensaje);
        }
      });
    }
  }

  indexarListas() {
    if (this.formulario) {
      this.formulario.submit = false;
    }
    if (this.formulario && this.formularioValue) {
      this.formulario.valido = true;
      this.formulario.id_registro_formulario = this.formularioValue.id_registro_formulario;
      this.formulario.campos.forEach(campo => {
        let registro_campo = this.formularioValue.campos.find(element => element.id_formulario_campo == campo.id_formulario_campo);
        if (registro_campo) {
          campo.valorAux = registro_campo.valor;
          campo.valor = registro_campo.valor;
          campo.otro_valor = registro_campo.otro_valor;
          campo.id_registro = registro_campo.id_registro;
        }
        if (campo.tipo_seleccion == 'MULTIPLE' && registro_campo) {
          this.setListaMultiple(campo, registro_campo);
        }
        if (campo.obligatorio == 'SI') {
          if (!campo.valor || campo.valor.trim() == '') {
            campo.valido = false;
            this.formulario.valido = false;
          } else {
            campo.valido = true;
          }
        }
      });
    }
    this.getFormulario.emit(this.formulario);
  }

  setListaMultiple(campo: Campo, campo_registro: Campo) {
    if (campo.tipo_seleccion != 'MULTIPLE') {
      return;
    }
    if (!campo_registro && this.formularioValue) {
      campo_registro = this.formularioValue.campos.find(element => element.id_formulario_campo == campo.id_formulario_campo);
    }
    if (!campo_registro) {
      return;
    }
    if (!campo_registro.valor) {
      return;
    }
    campo_registro.valor.split('|').forEach(element => {
      let item = campo.lista.find(val => val.id == element);
      if (item) {
        item.selected = true;
        if (item.id == campo.id_campo_otro) {
          campo.valorAux = item.id;
        }
      }
    });
  }

  getType(tipo) {
    let type = 'text';
    switch (tipo) {
      case 'F': type = "date"; break;
      case 'I': type = "number"; break;
      case 'S': type = "text"; break;
      case 'D': type = "number"; break;
      case 'A': type = "file"; break;
    }
    return type;
  }

  getLista(campo: Campo) {
    campo.lista = [];
    switch (campo.tipo) {
      case 'L':
        if (!campo.lista_value) {
          return;
        }
        campo.lista_value.split('|').forEach(item => {
          let values = item.split('=');
          campo.lista.push({ id: values[0], nombre: values[1], selected: false });
        });
        break;
      case 'LT':
        this.generalService.getListaTabla(campo.tabla, 'A', '').subscribe(data => {
          if (data.codigo == '1') {
            let campo_tabla = campo.campo_tabla.split(',');
            data.data.forEach(element => {
              campo.lista.push({ id: element[campo_tabla[0]], nombre: element[campo_tabla[1]], selected: false });
            });
            this.setListaMultiple(campo, null);
          } else {
            this.mensajeService.alertError(null, data.mensaje);
          }
        });
        break;
    }
  }

  selectMultiple(campo: Campo, item) {
    if (campo.id_campo_otro == item.id) {
      if (item.selected) {
        campo.valorAux = item.id;
      } else {
        campo.valorAux = null;
      }
    }
    campo.submit = true;
    this.grabarFormulario();
  }

  grabarCampo(campo: Campo) {
    if(!this.editable){
      return;
    }
    campo.submit = true;
    this.grabarFormulario();
  }

  grabarFormulario() {
    if(!this.editable){
      return;
    }
    this.formulario.valido = true;
    this.formulario.campos.forEach(campo => {
      let isValido = true;
      if (campo.tipo_seleccion == "MULTIPLE") {
        campo.valor = null;
        campo.lista.forEach(item => {
          if (item.selected) {
            campo.valor = campo.valor ? campo.valor + '|' + item.id : item.id;
            if(item.id == campo.id_campo_otro){
              if(!campo.otro_valor) isValido = false;
            }
          }
        });
      } else {
        campo.valor = campo.valorAux;
      }

      if (campo.obligatorio == 'SI') {
        if(!campo.valor) isValido = false;
        if(typeof campo.valor == 'number' && isNaN(campo.valor)) isValido = false;
        if(typeof campo.valor == 'string' && campo.valor.trim() == '') isValido = false;
        campo.valido = isValido;
        if(!isValido)this.formulario.valido = false;
      }
    });
    if (this.submit) {
      this.formulario.submit = this.submit;
      if (!this.formulario.valido) {
        this.submit = false;
        this.mensajeService.alertError(null, 'Faltan llenar campos');
        return;
      }
    }
    this.formularioService.grabarRegistroFormulario(this.formulario).subscribe(data => {
      if (data.codigo == '1') {
        //this.formulario = data.data;
        this.formulario.id_registro_formulario = data.data.id_registro_formulario;
        this.formulario.campos.forEach(campo=>{
          campo.id_registro = this.formulario.id_registro_formulario;
        });
        this.getFormulario.emit(this.formulario);
      } else {
        console.log(data);
        this.mensajeService.alertError(null, data.mensaje);
      }
    });
  }
}
