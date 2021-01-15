import { Component, OnInit, Input } from '@angular/core';
import { Usuario } from 'src/app/estructuras/usuario';
import { EventInput } from '@fullcalendar/core';
import { CatalogoService } from 'src/app/servicio/catalogo.service';
import { DashboardService } from 'src/app/servicio/Dashboard.service';

@Component({
  selector: 'app-mentor',
  templateUrl: './mentor.component.html',
  styleUrls: ['./mentor.component.css']
})
export class MentorComponent implements OnInit {

  usuario:Usuario;
  @Input() persona;
  calendarAgenda: EventInput[];
  public activeTab: string;

  resumen={total:0,pendientes:0,atendidos:0, enproceso:0};

  constructor(private catalogoService: CatalogoService, private dashboardService: DashboardService) {
    this.usuario = Usuario.getUser();
    this.activeTab = 'gallery';
   }

  ngOnInit() {
  }

  cargarAgenda(): void {
    this.catalogoService.post('agenda/agendaPersonal', {id_persona:this.persona.id_persona}).subscribe(data => {
      if(data.data instanceof Array){
        this.calendarAgenda = data.data as EventInput[];
      }
    });
  }

  getResumen(){
    this.dashboardService.getResumenAT().subscribe(data=>{
      if(data.codigo=="1"){
        this.resumen = data.data.resumen;
      }
    });
    setTimeout(() => {
      this.getResumen();
    }, 1000*60*15);
  }

}
