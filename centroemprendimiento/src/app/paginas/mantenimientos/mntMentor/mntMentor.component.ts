import { AfterViewInit, Component, OnDestroy, OnInit, Output, ViewChild, EventEmitter } from '@angular/core';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs';
import { Periodo } from 'src/app/componente/mntPeriodo/mntPeriodo.component';
import { General } from 'src/app/estructuras/General';
import { HorarioMentor, Mentor, PeriodoMentor } from 'src/app/estructuras/mentor';
import { Usuario } from 'src/app/estructuras/usuario';
import { MntMentorService } from 'src/app/servicio/mantenimiento/MntMentor.service';
import { MensajeService } from 'src/app/servicio/Mensaje.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-mntMentor',
  templateUrl: './mntMentor.component.html',
  styleUrls: ['./mntMentor.component.css']
})
export class MntMentorComponent implements OnInit, AfterViewInit, OnDestroy {

  @ViewChild(DataTableDirective, { static: false }) dtElement: DataTableDirective;
  @ViewChild('calificarModal', { static: false }) private calificarModal;

  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject();
  listaMentores: Mentor[];

  mentor: Mentor;
  usuario: Usuario;

  activeTab = "datos_personales";

  horarioRegistro: HorarioMentor[];

  /* Datos de rubrica*/
  id_rubrica = 2;
  id_evaluacion;
  mostrarCalificacion = false;
  hideHeader = true;
  hideFinalizar = false;
  hideGrabar = false;
  opciones = true;


  constructor(private mntMentorServicio: MntMentorService,
    private mensajeService: MensajeService) { }

  ngOnInit() {
    this.consultarMentores();
  }

  ngAfterViewInit(): void {
    this.dtTrigger.next();
  }

  ngOnDestroy(): void {
    // Do not forget to unsubscribe the event
    this.dtTrigger.unsubscribe();
  }

  rerender(): void {
    this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
      //dtInstance.clear().draw();
      // Destroy the table first
      dtInstance.destroy();
      // Call the dtTrigger to rerender again
      this.dtTrigger.next();
    });
  }

  pictNotLoading(event) {
    General.pictNotLoading(event);
  }

  consultarMentores() {
    this.mntMentorServicio.getMentores().subscribe(data => {
      if (data.codigo == '1') {
        this.listaMentores = data.data;
        this.rerender();
      }
    });
  }

  getFoto(url_foto) {
    return General.getFoto(url_foto);
  }

  selectMentor(mentor) {
    this.mntMentorServicio.getMentor(mentor.id_mentor).subscribe(data => {
      if (data.codigo == '1') {
        this.mentor = data.data;
        this.usuario = new Usuario(this.mentor);
        if (this.mentor.horarios.length == 0) {
          this.horarioRegistro = [];
          let dias = this.mentor.dias_disponibilidad.split(",");
          dias.forEach(dia => {
            this.horarioRegistro.push({ dia: dia, hora_fin: null, hora_inicio: null, id_mentor: this.mentor.id_mentor });
          });
        }else{
          this.horarioRegistro = this.mentor.horarios;
        }
        if (this.mentor.evaluacion) {
          this.id_evaluacion = this.mentor.evaluacion.id_evaluacion;
        }
        this.mentor.listaEjeMentoria.forEach(item=>{
          if(item.selected == 0){
            item.selected = false;
          }else{
            item.selected = true;
          }
        });
      } else {
        this.mensajeService.alertError(null, data.mensaje);
      }
    });
  }

  grabarHorario(horarios) {
    /*if (this.mentor.estado != 'V') {
      this.mensajeService.alertError(null, 'El mentor no tiene periodo vigente actual.');
      return;
    }*/
    this.setHorarios(horarios);
    this.mntMentorServicio.grabarHorarios(this.mentor.horarios, this.mentor.id_mentor).subscribe(data => {
      if (data.codigo == '1') {
        this.mensajeService.alertOK();
      } else {
        this.mensajeService.alertError(null, data.mensaje);
      }
    });
  }

  setHorarios(horarios) {
    this.mentor.horarios = horarios;
    this.mentor.horarios.forEach(data => {
      data.id_mentor = this.mentor.id_mentor;
    });
  }

  cancelar() {
    this.mentor = null;
    this.id_evaluacion = null;
  }

  getNombreEstado(estado) {
    let name = estado;
    switch (estado) {
      case 'A': name = "Aprobado"; break;
      case 'V': name = "Vigente"; break;
      case 'R': name = "Rechazado"; break;
      case 'I': name = "Registrado"; break;
    }
    return name;
  }

  getClassEstado(estado) {
    let name = "badge badge-dark";
    switch (estado) {
      case 'A': name = "badge badge-info"; break;
      case 'V': name = "badge badge-success"; break;
      case 'R': name = "badge badge-danger"; break;
      case 'I': name = "badge badge-warning"; break;
    }
    return name;
  }

  calificarMentor() {
    this.hideGrabar = false;
    this.hideFinalizar = false;
    this.calificarModal.show();
  }

  verCalificacionMentor() {
    this.hideGrabar = true;
    this.hideFinalizar = true;
    this.calificarModal.show();
  }

  evaluacion;
  crearUsuario = false;

  finalizarEvaluacion(evaluacion) {
    this.crearUsuario = false;
    this.evaluacion = evaluacion;
    this.mensajeService.confirmAlert(null, '¿Desea habilitar al mentor?', 'Aprobar mentor', 'Rechazar mentor')
      .then((result) => {
        let usu = Usuario.getUser();
        if (result.value) {
          this.mentor.estado = 'A';
        } else {
          this.mentor.estado = 'R';
        }
        this.mentor.id_usuario_aprob_rech = usu.id_usuario;
        this.mentor.fecha_aprobacion = General.getFechaActualHora();
        if (this.mentor.estado == 'A') {
          this.mensajeService.confirmAlert(null, '¿Desea crearle usuario al mentor?')
            .then((result2) => {
              if (result2.value) {
                this.crearUsuario = true;
              }
              this.grabarMentor();
            });
        } else {
          this.grabarMentor();
        }
      });
  }

  grabarMentor() {
    this.mntMentorServicio.grabarMentor(this.mentor, this.crearUsuario).subscribe(data=>{
      if(data.codigo=="1"){
        this.mentor.id_usuario = data.data.id_usuario;
        this.mensajeService.alertOK().then((result) => {
          this.calificarModal.hide();
        });
      }else{
        this.mensajeService.alertError(null, data.mensaje);
      }
    });
  }

  cancelarEvaluacion(evaluacion) {
    this.calificarModal.hide();
  }

  setPeriodos(periodos){
    
  }

  listaMentorDescarga:any[];
  
  grabarPeriodo(periodo: PeriodoMentor){
    periodo.id_mentor = this.mentor.id_mentor;
    this.mntMentorServicio.grabarPeriodoMentor(periodo).subscribe(data=>{
      if(data.codigo == '1'){
        this.mensajeService.alertOK();
        this.selectMentor(this.mentor);
      }else{
        this.mensajeService.alertError(null, data.mensaje);
      }
    });
  }

  descargar(){
    this.mntMentorServicio.getMentoresAllInfo().subscribe(data => {
      if (data.codigo == '1') {
        this.listaMentorDescarga = data.data;
      }
    });
  }

}
