import { Component, EventEmitter, Input, OnInit, Output, ViewChild } from '@angular/core';
import { WizardComponent } from 'angular-archwizard';
import { element } from 'protractor';
import { General } from 'src/app/estructuras/General';
import { Inscripcion } from 'src/app/estructuras/inscripcion';
import { Persona } from 'src/app/estructuras/persona';
import { Usuario } from 'src/app/estructuras/usuario';
import { EmprendedorInter } from 'src/app/interfaces/Emprendedor';
import { CatalogoService } from 'src/app/servicio/catalogo.service';
import { MensajeService } from 'src/app/servicio/Mensaje.service';
import { ProgramaService } from 'src/app/servicio/Programa.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-emprendedor',
  templateUrl: './emprendedor.component.html',
  styleUrls: ['./emprendedor.component.scss']
})
export class EmprendedorComponent implements OnInit {

  @Input() persona: Persona;
  @Input() usuario: Usuario;
  @Input() emprendedor: EmprendedorInter;
  @Output() selectActividad = new EventEmitter();
  @Output() salir = new EventEmitter();
  @Input() botonSalir: boolean=false;

  @ViewChild('asignarActividad', { static: false }) private asignarActividad;

  activeTab = "datos_personales";
  activeTabPrograma;

  id_sub_programa = 1;
  programa;

  columnas = 2;
  id_etapa: number;
  id_etapa_new: number;
  listaEtapa;

  etapaActual;

  asignarActividadesB=false;
  asignarMentoria = false;

  constructor(private programaService: ProgramaService,
    private mensajeService: MensajeService,
    private catalogoService: CatalogoService) {
    this.activeTabPrograma = 'todos';
  }

  ngOnInit() {
    this.consultarProgramaInscrito();
    this.consultarEtapas();
  }

  consultarProgramaInscrito() {
    this.programaService.getProgramaInscrito(this.id_sub_programa, this.id_etapa, this.emprendedor.id_persona).subscribe(data => {
      if (data.codigo == '1') {
        this.programa = data.data;
        this.armarDatosPrograma(data.data);
      } else {
        this.mensajeService.alertError(null, data.mensaje);
      }
    });
  }

  armarDatosPrograma(programa) {
    this.programa = programa;
    this.programa.etapa = {};
    this.programa.etapas.forEach(etapa => {
      if (etapa.id_etapa == this.programa.sub_programa.fase) {
        this.programa.etapa = etapa;
      }
    });
    this.programa.actividades.forEach(element => {
      element.selected = false;
      element.visible = true;
    });
    this.programa._actividades = this.programa.actividades;
    this.programa.actividades = this.programaService.armarArbolActividades(this.programa.actividades, null);
    if (!this.id_etapa) {
      this.id_etapa = this.programa.etapa.id_etapa;
      this.etapaActual = this.programa.etapa;
    }
  }

  consultarEtapas() {
    this.catalogoService.getEtapasXSubPrograma(this.id_sub_programa).subscribe(data => {
      if (data.codigo == '1') {
        this.listaEtapa = data.data;
      } else {
        this.mensajeService.alertError(null, 'Error en la carga de sub programa');
      }
    });
  }

  openEtapa(etapa): void {
    this.id_etapa = etapa.id_etapa;
    this.consultarProgramaInscrito();
  }

  _setActiveTab(tab) {
    this.activeTabPrograma = tab;
  }

  getActividades(actividades, tipo: number) {
    let lista = [];
    actividades.forEach(act => {
      act.visible = false;
      let subLista=[];
      switch (tipo) {
        case -1:
          act.visible = true;
          lista.push(act);
          break;
        case 0:
          if (act.id_tipo_actividad != 12 && act.id_tipo_actividad != 2) {
            if(this.agregarActividad(act, tipo)){
              lista.push(act);
            }
          }
          break;
        default:
          if (act.id_tipo_actividad == tipo) {
            if(this.agregarActividad(act, tipo)){
              lista.push(act);
            }
          }
          subLista=this.getActividades(act.child, tipo);
          if(subLista.length > 0){
            act.visible = true;
            lista.push(act);
          }
          break;
      }
    });
    return lista;
  }

  agregarActividad(act, tipo){
    if(act.child.length > 0){
      let subLista=this.getActividades(act.child, tipo);
      if(subLista.length > 0){
        act.visible = true;
        return true;
      }
    }else{
      act.visible = true;
      return true;
    }
    return false;
  }

  aprobarActividad(actividad) {
    if (!actividad.id_actividad_inscripcion) {
      this.mensajeService.alertError(null, 'No puede aprobar una actividad que no ha tomado aun el emprendedor');
      return;
    }
    Swal.fire({
      title: 'Confirmación!',
      html: "¿Está seguro que quiere aprobar esta actividad?",
      showCancelButton: true,
      type: 'info',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
      if (result.value) {
        let estadoAnt = actividad.estado_actividad_inscripcion;
        actividad.selected = true;
        actividad.estado_actividad_inscripcion = 'AP';
        actividad.id_usuario_aprobacion = Usuario.getUser().id_usuario;
        actividad.id_usuario_mod = actividad.id_usuario_aprobacion;
        actividad.fecha_aprobacion = General.getFechaActualHora();
        this.programaService.actualizar_actividad(actividad).subscribe(data => {
          if (data.codigo == '1') {
            this.mensajeService.alertOK();
            this.armarDatosPrograma(data.data.sub_programa);
          } else {
            actividad.estado_actividad_inscripcion = estadoAnt;
            this.mensajeService.alertError(null, data.mensaje);
          }
        });
      }
    })
  }

  aprobarActividades() {
    this.programa._actividades.forEach(actividad => {
      if(actividad.selected){
        actividad.estado_actividad_inscripcion = 'AP';
        actividad.id_usuario_aprobacion = Usuario.getUser().id_usuario;
        actividad.id_usuario_mod = actividad.id_usuario_aprobacion;
        actividad.fecha_aprobacion = General.getFechaActualHora();
      }
    });
    this.programaService.aprobarActividades(this.programa._actividades).subscribe(data => {
      if (data.codigo == '1') {
        this.mensajeService.alertOK();
      } else {
        let html = "<ul class='text-left' style='font-size: 13px'>";
        data.data.forEach(element => {
          html += '<li>' + element.data.actividad + ': ' + element.mensaje + '</li>';
        });
        html += "</ul>";
        this.mensajeService.alertHTML("Las siguientes actividades no fueron aprobadas: ", "<div class='row justify-content-center text-center' style='padding-right: 10px;padding-left: 10px;'>"
          + html
          + "</div>");
      }
      this.consultarProgramaInscrito();
    });
  }

  aprobarEtapa(){
    let mensaje = "¿Está seguro que desea aprobar la etapa?";
    let html = "<ul class='text-left' style='font-size: 13px'>";
    let faltaAprobar=false;
    this.programa._actividades.forEach(element => {
      if(element.estado_actividad_inscripcion != 'AP'){
        html += '<li>' + element.actividad + '</li>';
        faltaAprobar = true;
      }
    });
    html += "</ul>";
    if(faltaAprobar){
      mensaje = 'Las siguientes actividades no estan finalizadas: ' + html + '<br>' + mensaje;
    }
    Swal.fire({
      title: 'Confirmación!',
      html: mensaje,
      showCancelButton: true,
      type: 'info',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
      if (result.value) {
        this._aprobarEtapa();
      }
    })
  }

  _aprobarEtapa() {
    this.programaService.aprobarEtapa(this.programa.sub_programa.id_inscripcion, this.etapaActual.id_etapa, this.etapaActual.predecesor).subscribe(data => {
      if (data.codigo == '1') {
        this.mensajeService.alertOK();
      } else {
        this.mensajeService.alertHTML();
      }
    });
  }

  cambiarEtapa() {
    if(!this.id_etapa_new){
      this.mensajeService.alertError(null, 'Debe seleccionar la nueva etapa');
      return;
    }
    Swal.fire({
      title: 'Confirmación!',
      text: "¿Está seguro que desea cambiar la etapa del emprendedor?",
      showCancelButton: true,
      type: 'info',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
      if (result.value) {
        this._cambiarEtapa();
      }
    })
  }

  _cambiarEtapa() {
    this.programaService.cambiarFase(this.programa.sub_programa.id_inscripcion, this.etapaActual.id_etapa, this.id_etapa_new).subscribe(data => {
      if (data.codigo == '1') {
        this.mensajeService.alertOK();
      } else {
        this.mensajeService.alertError(null, data.mensaje);
      }
    });
  }

  revertirActividad(actividad) {
    if(actividad.estado_actividad_inscripcion != 'AP'){
      this.mensajeService.alertError(null, 'No puede revertir un estado diferente de aprobado');
      return;
    }
    Swal.fire({
      title: 'Confirmación!',
      text: "¿Está seguro que desea pasar la actividad a pendiente?",
      showCancelButton: true,
      type: 'info',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
      if (result.value) {
        this._revertirActividad(actividad);
      }
    })
  }

  _revertirActividad(actividad) {
    actividad.id_usuario_mod = Usuario.getUser().id_usuario;
    this.programaService.revertirActividad(actividad).subscribe(data => {
      if (data.codigo == '1') {
        this.mensajeService.alertOK();
        this.consultarProgramaInscrito();
      } else {
        this.mensajeService.alertError(null, data.mensaje);
      }
    });
  }

  listaActividades;
  

  asignarActividades(){
    this.asignarActividadesB=true;
  }

  asignarMentor(){
    this.asignarMentoria = true;
  }

  cancelar(){
    this.salir.emit();
  }

  cambiarMentorB=false;
  cambiarMentor(){
    this.cambiarMentorB = true;
  }
}
