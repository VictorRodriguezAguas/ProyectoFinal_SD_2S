import { AfterViewInit, Component, EventEmitter, Input, OnInit, Output, ViewChild } from '@angular/core';
import { WizardComponent } from 'angular-archwizard';
import { IOption } from 'ng-select';
import { General } from 'src/app/estructuras/General';
import { Mentoria } from 'src/app/interfaces/Mentoria';
import { CatalogoService } from 'src/app/servicio/catalogo.service';
import { MensajeService } from 'src/app/servicio/Mensaje.service';
import { ProgramaService } from 'src/app/servicio/Programa.service';

@Component({
  selector: 'app-asignarMentor',
  templateUrl: './asignarMentor.component.html',
  styleUrls: ['./asignarMentor.component.css']
})
export class AsignarMentorComponent implements OnInit, AfterViewInit {

  @ViewChild(WizardComponent) private wizard: WizardComponent;
  @ViewChild('asignarMentoria', { static: false }) private asignarMentoria;
  //@ViewChild('modalMentor', { static: false }) private modalMentor;
  @Output() cancelar = new EventEmitter<any>();
  @Output() grabar = new EventEmitter<any>();

  listaTemaMentoria = [];

  @Input() id_persona;
  @Input() id_inscripcion_etapa;
  @Input() id_actividad_inscripcion;
  @Input() id_reunion: number=null;

  listaMentoria:Mentoria[]=[];
  listaMentores:[]=[];

  inscripcionEtapa;

  constructor(private catalogoService: CatalogoService,
    private programaService: ProgramaService,
    private mensajeService: MensajeService) { }

  ngOnInit() {
    this.catalogoService.getListaEjesMentoria().subscribe(data => {
      if (data.codigo == '1') {
        this.listaTemaMentoria = data.data;
        this.listaTemaMentoria.forEach(item => {
          item.selected = false;
        })
      }
    });

    if (!this.id_inscripcion_etapa) {
      this.levantarModal = false;
      this.mensajeService.alertError(null, 'Falta parametro de inscripcion etapa. Consulte con el administrador').then(resul => {
        this.cancelar.emit();
      });
    }

    if (!this.id_persona) {
      this.levantarModal = false;
      this.mensajeService.alertError(null, 'No se ha seleccionado el emprendedor').then(resul => {
        this.cancelar.emit();
      });
    }

    if (this.levantarModal) {
      this.programaService.getInscripcionEtapa(this.id_inscripcion_etapa, this.id_actividad_inscripcion).subscribe(data=>{
        if(data.codigo == '1'){
          this.inscripcionEtapa = data.data;
          if(this.inscripcionEtapa.id_persona != this.id_persona){
            this.mensajeService.alertError(null, 'La inscripcion no pertenece al emprendedor').then(resul => {
              this.cancelar.emit();
            });
            this.levantarModal = false;
          }
        }else{
          this.mensajeService.alertError(null, data.mensaje);
        }
      });
    }
  }

  levantarModal = true;
  ngAfterViewInit() {
    setTimeout(() => {
      if (this.levantarModal)
        this.asignarMentoria.show();
    }, 500);
  }

  _cancelar() {
    this.asignarMentoria.hide();
    this.cancelar.emit();
  }

  validarMaxMentoria(){
    let cont = 0;
    this.listaTemaMentoria.forEach(item=>{
      if(item.selected){
        cont++;
      }
    });
    if(cont>this.inscripcionEtapa.max_mentoria){
      this.mensajeService.alertError(null, "Supero el máximo de mentoría por etapa.");
      return false;
    }
    if(cont == 0){
      this.mensajeService.alertError(null, "Debe seleccionar por lo menos 1 mentoría.");
      return false;
    }
    return true;
  }

  validarInicio(){
    if(!this.inscripcionEtapa){
      this.mensajeService.alertError(null, 'No existe la inscripcion de la etapa');
      this.levantarModal = false;
      return false;
    }
    if(!this.id_inscripcion_etapa){
      this.mensajeService.alertError(null, 'Faltan paramentros, Consulte al administrador de sistemas');
      this.levantarModal = false;
      return false;
    }
    if(this.inscripcionEtapa.id_persona != this.id_persona){
      this.mensajeService.alertError(null, 'La inscripcion no pertenece al emprendedor');
      this.levantarModal = false;
      return false;
    }
    return true;
  }

  paso2(){
    if(!this.validarMaxMentoria()){
      return;
    }
    if(!this.validarInicio()){
      return;
    }
    this.listaMentoria=[];
    this.listaTemaMentoria.forEach(item=>{
      if(item.selected){
        let mentoria:Mentoria = {id_persona:this.id_persona,
          id_mentor:null,
          id_eje_mentoria:item.id,
          nombre_emprendedor:null,
          nombre_mentor:null,
          id_inscripcion: this.inscripcionEtapa.id_inscripcion,
          id_inscripcion_etapa: this.id_inscripcion_etapa,
          id_etapa: this.inscripcionEtapa.id_etapa,
          cant: 1,
          tema_mentoria:item.nombre};
        this.listaMentoria.push(mentoria);
      }
    });
    this.wizard.goToNextStep();
  }

  paso3(){
    let grabar=true;
    let tema;
    this.listaMentoria.forEach(item=>{
      if(!item.mentor){
        grabar = false;
        tema = item.tema_mentoria;
      }
    });
    if(!grabar){
      this.mensajeService.alertError(null, 'No ha seleccionado el mentor para el tema: ' + tema);
      return;
    }
    this.wizard.goToNextStep();
  }

  mentoria:Mentoria;
  consultarMentor(item:Mentoria){
    this.mentoria = item;
    this.asignarMentoria.hide();
  }

  seleccionarMentor(mentor){
    this.mentoria.id_mentor = mentor.id_mentor;
    this.mentoria.nombre_mentor = mentor.apellido + ' ' + mentor.nombre;
    this.mentoria.mentor = mentor;
    this.cancelarMentor();
  }

  cancelarMentor(){
    this.mentoria = null;
    this.asignarMentoria.show();
  }

  grabarMentoria(){
    let grabar=true;
    let tema;
    let totalSesiones=0;
    this.listaMentoria.forEach(item=>{
      if(item.cant<=0){
        grabar = false;
        tema = item.tema_mentoria;
      }
      if(item.cant>=0){
        totalSesiones += item.cant;
      }
    });
    if(!grabar){
      this.mensajeService.alertError(null, 'Debe agregar por lo menos 1 sesion de mentoría en ' + tema);
      return;
    }
    if(this.inscripcionEtapa.max_horas_mentoria < totalSesiones){
      this.mensajeService.alertError(null, 'Has superado el máximo de sesiones permitidas');
      return;
    }
    this.programaService.asignarMentorias(this.inscripcionEtapa.id_inscripcion, this.id_inscripcion_etapa, this.listaMentoria, this.id_reunion).subscribe(data=>{
      console.log(data);
      if(data.codigo=='1'){
        this.asignarMentoria.hide();
        this.mensajeService.alertOK(null, "Asignación éxitosa").then(resp=>{
          let exportData:any={};
          exportData.mentorias = this.listaMentoria;
          exportData.actividades = data.data;
          console.log(exportData);
          //this.grabar.emit(exportData);
        });
      }else{
        this.mensajeService.alertError(null, data.mensaje);
      }
    });
  }

  getFoto(url_foto){
    return General.getFoto(url_foto);
  }

  pictNotLoading(event){
    General.pictNotLoading(event);
  }

  getTextoCorto(texto:string){
    return texto.substring(0, 100) + '...';
  }
}
