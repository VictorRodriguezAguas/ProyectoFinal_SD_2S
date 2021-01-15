import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Usuario } from 'src/app/estructuras/usuario';
import { Agenda } from 'src/app/interfaces/agenda';
import { AgendaService } from 'src/app/servicio/Agenda.service';
import { MensajeService } from 'src/app/servicio/Mensaje.service';

@Component({
  selector: 'app-reunionPage',
  templateUrl: './reunionPage.component.html',
  styleUrls: ['./reunionPage.component.scss']
})
export class ReunionPageComponent implements OnInit {

  agendaSeleccionada: Agenda;
  formulario;
  id_rubrica;
  id_agenda;
  tipo;
  editable=false;
  usuario:Usuario=Usuario.getUser();
  mostrarCalificacion:boolean = false;

  constructor(private route: ActivatedRoute, 
    private agendaService: AgendaService,
    private mensajeService: MensajeService) { }

  ngOnInit() {
    this.id_rubrica = +this.route.snapshot.paramMap.get('id_rubrica');
    this.formulario = this.route.snapshot.paramMap.get('formulario');
    this.id_agenda = this.route.snapshot.paramMap.get('id_agenda');
    this.tipo = this.route.snapshot.paramMap.get('tipo');
    console.log("Tipo: ",this.tipo);
    console.log("Usuario: ",this.usuario);
    if(this.tipo == 1 && (this.usuario.asistencia_tecnica || this.usuario.mentor)){
      this.editable = true;
    }
    console.log(this.editable);
    this.agendaService.getAgenda(this.id_agenda).subscribe(data=>{
      if(data.codigo == '1'){
        this.agendaSeleccionada = data.data;
        this.agendaSeleccionada.persona1 = data.data.nombre1;
        this.agendaSeleccionada.persona2 = data.data.nombre2;
        this.agendaSeleccionada.tipo_agenda=data.data.tipo;
        this.agendaSeleccionada.fecha_agenda=data.data.fecha;
        this.agendaSeleccionada.hora_inicio_agenda=data.data.hora_inicio;
        this.agendaSeleccionada.hora_fin_agenda=data.data.hora_fin;
        console.log(this.agendaSeleccionada);
        if(!this.agendaSeleccionada){
          this.mensajeService.alertError(null, "No se encontro datos").then(resp=>{
            this.goBack();
          });
        }
      }else{
        this.mensajeService.alertError(null, data.mensaje);
      }
    });
  }

  goBack() {
    window.history.back();
  }
}
