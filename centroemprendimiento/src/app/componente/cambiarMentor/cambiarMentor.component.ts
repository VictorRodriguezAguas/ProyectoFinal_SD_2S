import { AfterViewInit, Component, Input, OnInit, ViewChild } from '@angular/core';
import { General } from 'src/app/estructuras/General';
import { Mentoria } from 'src/app/interfaces/Mentoria';
import { MensajeService } from 'src/app/servicio/Mensaje.service';
import { ProgramaService } from 'src/app/servicio/Programa.service';

@Component({
  selector: 'app-cambiarMentor',
  templateUrl: './cambiarMentor.component.html',
  styleUrls: ['./cambiarMentor.component.scss']
})
export class CambiarMentorComponent implements OnInit, AfterViewInit {

  @ViewChild('modalMentor', { static: false }) private modalMentor;
  @Input() id_inscripcion;
  @Input() id_inscripcion_etapa;


  listaMentoria:Mentoria[];

  constructor(private programaService: ProgramaService, private mensajeService: MensajeService) { }

  ngOnInit() {
    console.log(this.id_inscripcion);
    console.log(this.id_inscripcion_etapa);
    this.programaService.getMentoriasPendientes(this.id_inscripcion, this.id_inscripcion_etapa).subscribe(data=>{
      console.log(data);
      if(data.codigo == '1'){
        this.listaMentoria = data.data;
      }
    });
  }

  ngAfterViewInit(){
    setTimeout(() => {
      this.modalMentor.show();
    }, 100);
  }

  cancelarCambio(){
    this.modalMentor.hide();
  }

  mentoria:Mentoria;
  consultarMentor(item:Mentoria){
    this.mentoria = item;
    this.modalMentor.hide();
  }

  seleccionarMentor(mentor){
    this.mentoria.id_mentor = mentor.id_mentor;
    this.mentoria.nombre_mentor = mentor.apellido + ' ' + mentor.nombre;
    this.mentoria.mentor = mentor;
    this.cancelarMentor();
  }

  cancelarMentor(){
    this.mentoria = null;
    this.modalMentor.show();
  }

  grabarMentoria(){

    this.mensajeService.confirmAlert(null, 'Se actualizará todas las sesiones que no han sido agendadas. <br><strong>¿Esta seguro que desea cambiar al mentor?</strong>').then(resp=>{
      if(resp.value){
        this.programaService.cambiarMentores(this.listaMentoria).subscribe(data=>{
          console.log(data);
          if(data.codigo=='1'){
            this.cancelarCambio();
            this.mensajeService.alertOK(null, "Asignación éxitosa");
          }else{
            this.mensajeService.alertError(null, data.mensaje);
          }
        });
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
