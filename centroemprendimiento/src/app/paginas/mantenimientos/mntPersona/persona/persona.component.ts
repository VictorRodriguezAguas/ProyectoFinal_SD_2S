import { DatePipe } from '@angular/common';
import { HttpParams } from '@angular/common/http';
import { AfterViewInit, Component, Input, OnInit, Output, ViewChild, EventEmitter } from '@angular/core';
import { Persona } from 'src/app/estructuras/persona';
import { CatalogoService } from 'src/app/servicio/catalogo.service';
import { MensajeService } from 'src/app/servicio/Mensaje.service';
import { PersonaService } from 'src/app/servicio/Persona.service';
import { UsuarioService } from 'src/app/servicio/Usuario.service';

@Component({
  selector: 'app-persona',
  templateUrl: './persona.component.html',
  styleUrls: ['./persona.component.scss']
})
export class PersonaComponent implements OnInit, AfterViewInit {

  @ViewChild('editarModal', { static: false }) private editarModal;

  @Input() registro;
  @Output() grabar=new EventEmitter<any>();
  @Output() cancelar=new EventEmitter<any>();

  cod_trama: string = "MXUSU001";
  birthDateStr: string = '';
  nombre: string = "";
  estado: string = "T";
  isCreate: boolean = false;
  persona: Persona;
  usuario;
  userMail;
  radioUsu;
  birthDate;
  citiesLst;
  jobSituationLst;
  academicGradeLst;
  interestedDueLst;
  institutionLst;
  provinciasList;

  constructor(private catalogoService: CatalogoService,
    private mensajeService: MensajeService,
    private _datePipeService: DatePipe,
    private personaService: PersonaService,
    private usuarioService: UsuarioService) { }

  ngOnInit() {
    this.getCitiesLst();
    this.getJobSituationLst();
    this.getAcademicGradeLst();
    this.getInterestedDueLst();
    this.getInstitutionLst();
    this.catalogoService.getUbicaciones().subscribe(data => {
      if (data.codigo == '1') {
        this.provinciasList = data.data;
      }
    });
    this.birthDate = this.registro.fecha_nacimiento;
    this.birthDateStr = this.registro.fecha_nacimiento;
    if(!this.registro.id_usuario){
      this.isCreate = true;
    }
  }

  ngAfterViewInit(){
    setTimeout(() => {
      this.editarModal.show();
    }, 50);
  }

  public isEmpty(str: string) {
    return (!str || 0 === str.length);
  }

  jsonDateToString(time) {
    let newDate = '' + time.month + '/' + time.day + '/' + time.year;
    return newDate;
  }

  strDateToLong(timeStr) {
    let newDate = new Date(timeStr).valueOf();
    return newDate;
  }

  formatDateReport(date) {
    return this._datePipeService.transform(date, 'yyyy-MM-dd');
  }

  getCitiesLst() {
    if (this.registro) {
      this.catalogoService.getUbicaciones(null, this.registro.id_provincia).subscribe(data => {
        if (data.codigo == '1') {
          this.citiesLst = data.data;
        }
      });
    }
  }

  getJobSituationLst() {
    this.catalogoService.getListaSituacionLaboral().subscribe(data => {
      if (data.codigo == '1') {
        this.jobSituationLst = data.data;
      }
    });
  }

  getAcademicGradeLst() {
    this.catalogoService.getListaNivelAcademico().subscribe(data => {
      if (data.codigo == '1') {
        this.academicGradeLst = data.data;
      }
    });
  }

  getInterestedDueLst() {
    this.catalogoService.getListaInteresCentroEmprendimiento().subscribe(data => {
      if (data.codigo == '1') {
        this.interestedDueLst = data.data;
      }
    });
  }

  getInstitutionLst() {
    this.catalogoService.getInstitutionLst().subscribe(data => {
      if (data.codigo == '1') {
        this.institutionLst = data.data;
      }
    });
  }

  birthDateBrowse(newStartDate) {
    try {
      this.birthDate = newStartDate
      this.birthDateStr = this.jsonDateToString(this.birthDate);
      this.birthDateStr = this.formatDateReport(new Date(this.birthDateStr));
    } catch (error) {
      console.log(error);
    }
  }

  isValidCardID(cardID: string): boolean {
    var total = 0, i;
    var longitud;

    if (cardID === undefined) {
      return false;
    } else {
      longitud = cardID.length;
      if (cardID !== "" && longitud === 10) {
        for (i = 0; i < (longitud - 1); i++) {
          if (i % 2 === 0) {
            var aux = parseInt(cardID.charAt(i)) * 2;
            if (aux > 9) aux -= 9;
            total += aux;
          } else {
            total += parseInt(cardID.charAt(i));
          }
        }
        total = total % 10 ? 10 - total % 10 : 0;
        if (parseInt(cardID.charAt(longitud - 1)) == total)
          return true;
        else
          return false;
      }
    }
  }

  preGrabar() {
    if (this.radioUsu) {
      this.insertUser();
    } else {
      this._grabar();
    }
  }

  _grabar() {
    if (this.registro.tipo_identificacion == 'C') {
      if (!this.isValidCardID(this.registro.identificacion)) {
        this.mensajeService.alertError(null, 'Cédula debe ser válida.');
        return;
      }
    }
    this.registro.fecha_nacimiento = this.birthDateStr;
    if (this.registro.fecha_nacimiento === undefined) {
      this.mensajeService.alertError(null, 'Debe ingresar Fecha Nacimiento.');
    } else if (this.registro.fecha_nacimiento === null) {
      this.mensajeService.alertError(null, 'Debe ingresar Fecha Nacimiento.');
    } else {
      if (this.registro.id_usuario === undefined || this.registro.id_usuario === null)
        this.registro.id_usuario = null;
      this.registro.fecha_nacimiento = this.birthDateStr;
      this.personaService.grabarPersona(this.registro)
        .subscribe(data => {
          if (data.codigo == '1') {
            this.mensajeService.alertOK();
            this.editarModal.hide();
            this.grabar.emit(data.data);
          } else {
            this.mensajeService.alertError(null, data.mensaje);
          }
        });
    }
  }

  insertUser() {
    if (this.registro.tipo_identificacion == 'C') {
      if (!this.isValidCardID(this.registro.identificacion)) {
        this.mensajeService.alertError(null, 'Cédula debe ser válida.');
        return;
      }
    }
    if (this.registro.id_institucion === undefined) {
      this.mensajeService.alertError(null, 'Debe especificar institucion');
    } else {
      if (this.registro.id_institucion == '') {
        this.mensajeService.alertError(null, 'Debe especificar institucion');
      } else {
        if (this.registro.email === undefined || this.registro.email === null) {
          this.mensajeService.alertError(null, 'Llene campos requeridos.');
        } else if (this.registro.nombre === undefined || this.registro.nombre === null) {
          this.mensajeService.alertError(null, 'Llene campos requeridos.');
        } else if (this.registro.apellido === undefined || this.registro.apellido === null) {
          this.mensajeService.alertError(null, 'Llene campos requeridos.');
        } else {
          this.usuario = {
            "usuario": this.registro.email,
            "nombre": this.registro.nombre,
            "apellido": this.registro.apellido,
            "correo": this.registro.email,
            "id_institucion": this.registro.id_institucion,
            "id_persona": this.registro.id,
            "foto": '',
            "password": this.registro.identificacion,
            "estado": 'A'
          }

          this.usuarioService.insertUser(this.usuario)
            .subscribe(data => {
              if (data.codigo == '1') {
                this.usuario = data.data;
                this.registro.id_usuario = this.usuario.id;
                this._grabar();
                this.sendUserMail();
              } else {
                this.mensajeService.alertError(null, data.mensaje);
              }
            });
        }
      }
    }
  }

  sendUserMail() {
    this.userMail = { "id_persona": this.registro.id }
    const body = new HttpParams()
      .set('cod_trama', this.cod_trama)
      .set('datos', JSON.stringify(this.userMail))
      .set('email', this.registro.email);

    this.usuarioService.sendUserMail(body)
      .subscribe(data => {
        if (data.codigo == '1') {
        } else {
          this.mensajeService.alertError(null, "Error en el envío de correo"+data.mensaje);
        }
      });
  }

  _cancelar(){
    this.cancelar.emit(this.registro);
    this.editarModal.hide();
  }
}
