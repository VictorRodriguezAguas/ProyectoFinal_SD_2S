import { Component, OnInit, Input } from '@angular/core';
import { Usuario } from 'src/app/estructuras/usuario';
import { EventInput } from '@fullcalendar/core';
import { EventoService } from 'src/app/servicio/Evento.service';
import { DashboardService } from 'src/app/servicio/Dashboard.service';

@Component({
  selector: 'app-asistenciaTecnica',
  templateUrl: './asistenciaTecnica.component.html',
  styleUrls: ['./asistenciaTecnica.component.css']
})
export class AsistenciaTecnicaComponent implements OnInit {

  usuario:Usuario;
  @Input() persona;
  calendarAgenda: EventInput[];
  public activeTab: string;

  talleres;

  resumen={total:0,pendientes:0,atendidos:0, enproceso:0};

  constructor(private eventoService: EventoService, private dashboardService: DashboardService) {
    this.usuario = Usuario.getUser();
    this.activeTab = 'gallery';
   }

  ngOnInit() {
    this.consultarTalleres();
    this.getResumen();
  }

  consultarTalleres(){
    this.eventoService.getEventos({estado:'A'}).subscribe(data=>{
      if(data.codigo=="1"){
        if(data.data){
          data.data.forEach(item => {
            item.stick = true;
            item.title = item.nombre;
            item.start = item.fecha + 'T'+item.hora_inicio;
            item.end = item.fecha + 'T'+item.hora_fin;
            item.url1=null;
            item.url=undefined;
          });
        }
        this.talleres = data.data;
      }
      document.querySelector('.nav-pills').classList.add('navemp');
    });
  }

  clickEvent(evento){
    //this.mensajeService.alertInfo(null, 'Puedes regístrarte a este taller en la sección "Actividades"');
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
