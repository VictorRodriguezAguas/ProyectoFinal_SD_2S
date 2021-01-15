import { HttpResponse } from '@angular/common/http';
import { AfterViewInit, Component, EventEmitter, Input, OnInit, Output, ViewChild } from '@angular/core';
import { Persona } from 'src/app/estructuras/persona';
import { Ticket } from 'src/app/estructuras/Ticket';
import { Agenda } from 'src/app/interfaces/agenda';
import { Archivos } from 'src/app/interfaces/archivos';
import { EmprendedorInter } from 'src/app/interfaces/Emprendedor';
import { Reunion } from 'src/app/interfaces/reunion';
import { AsistentetecnicoService } from 'src/app/servicio/Asistentetecnico.service';
import { EmprendedorService } from 'src/app/servicio/Emprendedor.service';
import { MensajeService } from 'src/app/servicio/Mensaje.service';
import { PersonaService } from 'src/app/servicio/Persona.service';
import { DatePipe, formatDate } from '@angular/common';
import { Formulario } from 'src/app/interfaces/Formulario';
import { ArchivoComponent } from '../archivo/archivo.component';
import { SoporteService } from 'src/app/servicio/Soporte.service';
import * as moment from 'moment';
import * as fileSaver from 'file-saver';
import { Meeting } from 'src/app/estructuras/Meeting';
import { Usuario } from 'src/app/estructuras/usuario';
import { General } from 'src/app/estructuras/General';
import { CatalogoService } from 'src/app/servicio/catalogo.service';
import { ProgramaService } from 'src/app/servicio/Programa.service';
import { conDetalle, EvaluacionService } from 'src/app/servicio/Evaluacion.service';
import { Actividad_edicion } from 'src/app/estructuras/actividad_edicion';

@Component({
  selector: 'app-reunion',
  templateUrl: './reunion.component.html',
  styleUrls: ['./reunion.component.css']
})
export class ReunionComponent implements OnInit, AfterViewInit {

  temas = '';
  acuerdos = '';
  observacion = '';

  @Input() agendaSeleccionada: Agenda;
  @Input() vista:number=1;
  @Input() codFormulario: string;
  @Input() editable: boolean = true;
  @Input() id_rubrica;
  @Input() hideDatosEmprendedor:boolean = false;
  @Input() hideAsignarActividad:boolean = false;
  @Input() hideAsignarMentor:boolean = false;
  @Input() hideCalificar:boolean = false;
  @Input() mostrarCalificacion = false;

  @Input() titleExpectativas:string = 'Expectativas';
  @Input() titleCompromisos:string = 'Compromisos';
  @Input() titleObservaciones:string = 'Observaciones';

  @Output() cancelar = new EventEmitter<any>();
  @Output() finalizar = new EventEmitter<any>();

  emprendedor: EmprendedorInter;
  archivos: Archivos[];
  reunion: Reunion;
  persona: Persona;
  configuration = new Meeting();
  configurations: any[];
  usuario: Usuario = Usuario.getUser();

  actividadInscripcion: Actividad_edicion;

  imagen1;
  imagen2;
  nemonicoFile = { nemonico: 'IMAGEN_INICIO_REUNIO', mimetype: 'imagen/*', size_max: 1 };

  public showView = false;
  public showFR = false;
  public showIR = true;

  ticket: Ticket = new Ticket();

  constructor(private emprendedorService: EmprendedorService,
    private asistenteTecnicoService: AsistentetecnicoService,
    private soporteService: SoporteService,
    private mensajeService: MensajeService,
    private _datePipeService: DatePipe,
    private evaluacionService: EvaluacionService,
    private catalogoService: CatalogoService,
    private personaService: PersonaService,
    private programaService: ProgramaService) { }

  ngOnInit() {
    console.log('Editable: ',this.editable);
    this.getMeetingConfigs();
    this.catalogoService.getNemonicoFile(this.nemonicoFile.nemonico).subscribe(data => {
      if (data.codigo == '1') {
        this.nemonicoFile = data.data;
      }
    });
    console.log(this.mostrarCalificacion);
  }

  ngAfterViewInit() {
    console.log(this.agendaSeleccionada);
    this.getMeetingByAgenda(this.agendaSeleccionada.id_agenda);
    this.getPersona(this.agendaSeleccionada.id_persona);
    this.getEmprendedor(this.agendaSeleccionada.id_persona);
    this.getArchivos(this.agendaSeleccionada.id_persona, 'MODELO_NEGOCIO');
    setTimeout(() => {
      if(this.hideDatosEmprendedor){
        this.vista = 1;
      }
      this.getActividadInscripcion(this.agendaSeleccionada.id_agenda);
    }, 500);
  }

  getMeetingByAgenda(idAgenda: number): void {
    this.asistenteTecnicoService.getMeetingByAgenda(idAgenda)
      .subscribe(data => {
        if (data.codigo == '1') {
          this.reunion = data.data;
          console.log('Reunion');
          console.log(this.reunion);
          if (!this.reunion) {
            this.showFR = false;
            this.showIR = true;
          } else {
            this.consultarEvaluacion();
            this.temas = this.reunion.temas;
            this.observacion = this.reunion.observacion;
            this.acuerdos = this.reunion.acuerdos;
            if (this.reunion.estado != "AP") {
              this.showFR = true;
              this.showIR = false;
            } else {
              this.showFR = true;
              this.showIR = false;
            }
          }
        } else {
          console.log(data.mensaje);
        }
      });
  }

  getActividadInscripcion(idAgenda){
    this.programaService.getActividadInscripcionXAgenda(idAgenda).subscribe(data=>{
      if(data.codigo == '1'){
        this.actividadInscripcion = data.data;
      }
      if(!this.actividadInscripcion && !this.hideAsignarActividad){
        let mensaje = "No se pudo habilitar la opción de asignar actividades debido a que no se pudo identificar la etapa a la que pertenece el emprendedor";
        console.log(mensaje);
        this.mensajeService.alertError(null, mensaje);
      }
    });
  }

  consultarEvaluacion() {
    let param = {
      id_reunion: this.reunion.id_reunion,
      id_evaluado: this.agendaSeleccionada.id_persona,
      id_evaluador: this.agendaSeleccionada.id_persona2
    };
    this.evaluacionService.getEvaluacionxIds(param, this.id_rubrica, conDetalle.NO).subscribe(data => {
      if (data.codigo == '1') {
        if (data.data) {
          this.id_evaluacion = data.data.id_evaluacion;
        }
      }
    });
  }

  getPersona(idPersona: number): void {
    this.personaService.getPersona(idPersona)
      .subscribe(data => {
        if (data.codigo == '1') {
          this.persona = data.data;
        } else {
          console.log(data.mensaje);
        }
      });
  }

  getEmprendedor(idPersona: number): void {
    this.emprendedorService.getEmprendedorAT(idPersona)
      .subscribe(
        respuesta => {
          if (respuesta.codigo == '1') {
            this.emprendedor = respuesta.data;
          }
        }
      );
  }

  getArchivos(idPersona: number, daemon: string): void {
    this.emprendedorService.getArchivos(idPersona, daemon)
      .subscribe(
        archivos => {
          this.archivos = archivos;
          this.showView = true;
        }
      );
  }

  showStartAgenda(): boolean {
    if (this.reunion)
      return true;
    else
      return false;
  }

  showEndAgenda(): boolean {
    if (this.reunion) {
      return false;
    } else {
      if (this.reunion.estado != "AP")
        return true;
      else
        return false;
    }
  }

  isFilesEmpty(): boolean {
    if (this.archivos) {
      if (this.archivos.length > 0)
        return true;
      else return false;
    } else
      return false;
  }

  expectativas(lista: string[]) {
    this.temas = lista.join('|');
    this.actualizarReunion();
  }

  compromisos(lista: string[]) {
    this.acuerdos = lista.join('|');
    this.actualizarReunion();
  }

  observaciones(lista: string[]) {
    this.observacion = lista.join('|');
    this.actualizarReunion();
  }

  setFormulario(formulario: Formulario) {
    if (!this.reunion.id_registro_formulario && formulario.id_registro_formulario) {
      this.reunion.id_registro_formulario = formulario.id_registro_formulario;
      this.actualizarReunion();
    }
    this.formulario = formulario;
  }

  setReunion() {
    if (this.reunion) {
      this.reunion.temas = this.temas;
      this.reunion.acuerdos = this.acuerdos;
      this.reunion.observacion = this.observacion;
      this.reunion.tipo_reunion = "";
      this.reunion.url_reunion = "";
      this.reunion.estado = "EP";
    }
  }

  actualizarReunion() {
    this.setReunion();
    this.grabarReunion();
  }

  iniciarReunion(): void {
    var hourStart = this.agendaSeleccionada.hora_inicio_agenda.split(':')[0] + ':'
      + this.agendaSeleccionada.hora_inicio_agenda.split(':')[1];
    //if (this.isMeetingDay(this.agendaSeleccionada.fecha_agenda + " " + hourStart)) {
      const today = new Date();
      const time = today.getHours() + ':' + today.getMinutes()
      const formatedToday = formatDate(today, 'yyyy-MM-dd HH:mm:ss', 'en-US');
      let id_reunion = null;
      let id_registro_formulario = null;
      if (this.agendaSeleccionada.id_reunion) {
        id_reunion = this.agendaSeleccionada.id_reunion;
      }
      if (this.agendaSeleccionada.id_registro_formulario) {
        id_registro_formulario = this.agendaSeleccionada.id_registro_formulario;
      }
      this.reunion = {
        id_reunion: id_reunion,
        id_registro_formulario: id_registro_formulario,
        id_agenda: this.agendaSeleccionada.id_agenda,
        hora_inicio_agenda: this.agendaSeleccionada.hora_inicio_agenda.split(":")[0]
          + ":" + this.agendaSeleccionada.hora_inicio_agenda.split(":")[1],
        hora_fin_agendad: this.agendaSeleccionada.hora_fin_agenda.split(":")[0]
          + ":" + this.agendaSeleccionada.hora_fin_agenda.split(":")[1],
        fecha_agendada: this.agendaSeleccionada.fecha_agenda,
        hora_inicio: time,
        hora_fin: null,
        temas: null,
        acuerdos: null,
        observacion: null,
        archivo: null,
        estado: "EP",
        tipo_reunion: null,
        url_reunion: null,
        id_actividad_inscripcion: null
      };

      let formData = new FormData();
      formData.append('datos', JSON.stringify(this.reunion));

      this.asistenteTecnicoService.iniciarReunion(formData).subscribe(data => {
        if (data instanceof HttpResponse) {
          if (data.body.codigo == '0') {
            this.mensajeService.alertError(null, data.body.mensaje);
          } else {
            this.reunion = data.body.data;
            this.temas = this.reunion.temas;
            this.acuerdos = this.reunion.acuerdos;
            this.observacion = this.reunion.observacion;
            if (this.reunion.estado != 'AP')
              this.reunion.estado = "EP";
            this.showFR = true;
            this.showIR = false;
            /*window.open('https://docs.google.com/forms/d/e/1FAIpQLSe1WHBsnSel3sJ8bI_PG43fbrNi2efQyDqPzwaXXOcPaHMFSA/viewform?entry.847425255=' + this.reunion.id_reunion +
              '&entry.560905001=' + this.agendaSeleccionada.persona2 +
              '&entry.930274099=' + this.agendaSeleccionada.persona1 +
              '&entry.1577321074=' + this.agendaSeleccionada.telefono +
              '&entry.925111284=' + this.agendaSeleccionada.fecha_agenda +
              '&entry.547686735=' + this.reunion.hora_inicio +
              '&entry.1381627069=' + this.reunion.hora_fin, '_blank');
            this.actualizarActividad(this.reunion);*/
          }
        }
      });
    /*} else {
      this.mensajeService.alertError(null, "Fecha Reunión no corresponde.");
    }*/
  }

  isMeetingDay(dateStr: string): boolean {
    var todayStr = this.formatDateMeeting(new Date());
    var today = moment(todayStr);
    var originDate = moment(dateStr);
    if (originDate.diff(today, 'days') == 0) {
      if (originDate.diff(today, 'hours') <= parseInt(this.configuration.HORAS_MIN_REUNION_INI)) {
        if (originDate.diff(today, 'minutes') <= parseInt(this.configuration.HORAS_MIN_REUNION_INI) * 60)
          return true;
        else
          return false;
      } else { return false; }
    } else {
      return false;
    }
  }

  getMeetingConfigs(): void {
    this.asistenteTecnicoService.getMeetingConfigs()
      .subscribe(data => {
        if (data.codigo == '1') {
          this.configurations = data.data;
          for (let entry of this.configurations) {
            switch (entry.nombre) {
              case 'HORAS_MIN_AGENDA_CAN':
                this.configuration.HORAS_MIN_AGENDA_CAN = entry.valor;
                break;
              case 'HORAS_MIN_AGENDA_AT':
                this.configuration.HORAS_MIN_AGENDA_AT = entry.valor;
                break;
              case 'MAX_DIAS_AGENDA_AT':
                this.configuration.MAX_DIAS_AGENDA_AT = entry.valor;
                break;
              case 'HORAS_MIN_REUNION_INI':
                this.configuration.HORAS_MIN_REUNION_INI = entry.valor;
                break;
              default:
                break;
            }
          }
        } else {
          console.log(data.mensaje);
        }
      });
  }

  formatDateMeeting(date) {
    return this._datePipeService.transform(date, 'yyyy-MM-dd hh:mm');
  }

  grabarReunion() {
    let formData = new FormData();
    formData.append('datos', JSON.stringify(this.reunion));
    formData.append('imagen1', this.imagen1);
    formData.append('imagen2', this.imagen2);
    this.asistenteTecnicoService.finalizarReunion(formData)
      .subscribe(data => {
        if (data instanceof HttpResponse) {
          if (data.body.codigo == '0') {
            this.mensajeService.alertError(null, data.body.mensaje);
          } else {
            if (this.reunion.estado == "AP") {
              this.reunion = data.body.data;
              this.showFR = false;
              this.showIR = false;
              this.insertTicket();
              this.actualizarActividad(this.reunion);
            }
          }
        }
      });
  }

  public insertTicket() {
    try {
      this.ticket.id_ticket = 0;
      this.ticket.id_tipo = 1;
      this.ticket.id_usuario_creacion = this.persona.id_usuario;
      this.ticket.id_persona = this.persona.id;
      this.ticket.id_categoria = 10;
      this.ticket.id_reunion = this.reunion.id_reunion;
      this.ticket.id_subcategoria = null;
      this.ticket.fecha_creacion = this.getFormattedDate();
      this.ticket.descripcion = "Generado por asistencia tecnica.";

      let formData = new FormData();
      formData.append('datos', JSON.stringify(this.ticket));

      this.soporteService.insertWithForm(formData).subscribe(data => {
        if (data instanceof HttpResponse) {
          if (data.body.codigo == '0') {
            console.log(data.body.mensaje);
          } else {
            console.log(data.body.mensaje);
          }
        }
      });

    } catch (error) {
      console.log(error);
    }
  }

  actualizarActividad(reunion: Reunion) {
    this.asistenteTecnicoService.actualizarActividad(reunion)
      .subscribe(data => {
        if (data.codigo == '1') {
          this.mensajeService.alertOK(null, data.mensaje).then(()=>{
            window.history.back();
          });
        } else {
          this.mensajeService.alertError(null, data.mensaje);
        }
      });
  }

  submit = false;
  formulario: Formulario;
  finalizarReunion(): void {
    if (!this.evaluacionFinalizada && !this.hideCalificar) {
      this.mensajeService.alertError(null, 'Debe finalizar la calificación del emprendimiento');
      return;
    }
    if (this.agendaSeleccionada.id_tipo_asistencia == 2) {
      if(!this.imagen1){
        this.mensajeService.alertError(null, 'La reunion es ONLINE por lo que debe ingresar la imagen de inicio de sesion virtual');
        return;
      }
    }
    this.submit = true;
    if (!this.formulario.valido) {
      setTimeout(() => { this.submit = false; }, 500);
      console.log('No formulario');
      return;
    }
    if (this.agendaSeleccionada.id_tipo_asistencia == 2) {
      if(!this.imagen2){
        this.mensajeService.alertError(null, 'La reunion es ONLINE por lo que debe ingresar la imagen de fin de sesion virtual');
        return;
      }
    }
    try {
      if (!this.acuerdos || this.acuerdos.length == 0) {
        this.mensajeService.alertError(null, 'Debe ingresar los compromisos');
        return;
      }
      this.execFinalizarReunion();
    } catch (error) {
      console.log(error);
    }
  }

  guardarArchivoReunion(): void {
    let formData = new FormData();

    formData.append('archivoReunion', ArchivoComponent.archivo);
    this.asistenteTecnicoService.guardarArchivoReunion(formData).subscribe(data => {
      if (data instanceof HttpResponse) {
        if (data.body.codigo == '0') {
          this.mensajeService.alertError(null, data.body.mensaje);
        } else {
          this.reunion.archivo = data.body.data;
          this.execFinalizarReunion();
        }
      }
    });
  }

  execFinalizarReunion(): void {
    const today = new Date();
    const time = today.getHours() + ':' + today.getMinutes()
    const formatedToday = formatDate(today, 'yyyy-MM-dd HH:mm:ss', 'en-US');
    if (this.reunion) {
      this.setReunion();
      this.reunion.hora_fin = time;
      this.reunion.estado = "AP";
    }
    General.loading();
    this.grabarReunion();
  }

  public getFormattedDate() {
    var ddStr: string = '';
    var mmStr: string = '';
    var hrStr: string = '';
    var mnStr: string = '';
    var scStr: string = '';
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    var hour = today.getHours();
    var mint = today.getMinutes();
    var secd = today.getSeconds();

    (dd < 10) ? ddStr = "0" + dd : ddStr = "" + dd;
    (mm < 10) ? mmStr = "0" + mm : mmStr = "" + mm;
    (hour < 10) ? hrStr = "0" + hour : hrStr = "" + hour;
    (mint < 10) ? mnStr = "0" + mint : mnStr = "" + mint;
    (secd < 10) ? scStr = "0" + secd : scStr = "" + secd;

    return "" + yyyy + "-" + mmStr + "-" + ddStr + " " + hrStr + ":" + mnStr + ":" + scStr;
  }

  setImagen1(file) {
    this.imagen1 = file;
  }

  setImagen2(file) {
    this.imagen2 = file;
  }

  /* Asignacion de actividades */
  asignarActividadesB = false;
  asignarActividades() {
    this.asignarActividadesB = true;
  }

  /* Datos de rubrica*/
  @ViewChild('calificarEmprendimientoModal', { static: false }) private calificarEmprendimientoModal;
  id_evaluacion;
  hideHeader = true;
  hideFinalizar = false;
  hideGrabar = false;
  opciones = true;

  evaluar = false;

  calificarEmprendimiento() {
    this.hideGrabar = false;
    this.hideFinalizar = false;
    this.evaluar = true;
    this.calificarEmprendimientoModal.show();
  }

  cancelarEvaluacion(evaluacion) {
    this.evaluar = false;
    this.calificarEmprendimientoModal.hide();
  }

  evaluacionFinalizada = false;
  finalizarEvaluacion(evaluacion) {
    this.evaluacionFinalizada = true;
    this.calificarEmprendimientoModal.hide();
  }

  getDato(value) {
    return value ? value : 'Sin información';
  }

  public pictNotLoading(event) { General.pictNotLoading(event); }

  downloadFile(archivo: Archivos) {
    const fileUrl = archivo.url_archivo + archivo.modelo_negocio_archivo;
    this.asistenteTecnicoService.downloadFile(fileUrl)
      .subscribe(response => {
        let blob: any = new Blob([response], { type: 'text/json; charset=utf-8' });
        const url = window.URL.createObjectURL(blob);
        fileSaver.saveAs(blob, archivo.modelo_negocio_archivo);
      }), error => console.log('Error downloading the file'),
      () => console.info('File downloaded successfully');
  }

  strDateToLong(timeStr) {
    let newDate = new Date(timeStr).valueOf();
    return newDate;
  }

  formatDateReport(date) {
    return this._datePipeService.transform(date, 'yyyy-MM-dd');
  }

  _cancelar() {
    this.showView = !this.showView
    this.cancelar.emit();
  }

}
