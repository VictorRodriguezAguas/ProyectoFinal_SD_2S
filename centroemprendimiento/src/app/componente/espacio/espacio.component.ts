import { Component, OnInit } from '@angular/core';
import { ChartOptions, ChartType, ChartDataSets } from 'chart.js';
import { Label, Color } from 'ng2-charts';
import { FullCalendarComponent } from '@fullcalendar/angular';
import { EventInput } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGrigPlugin from '@fullcalendar/timegrid';
import listView from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import esLocale from '@fullcalendar/core/locales/es';
import Swal from 'sweetalert2';
import * as moment from 'moment';

@Component({
  selector: 'app-espacio',
  templateUrl: './espacio.component.html',
  styleUrls: ['./espacio.component.scss']
})
export class EspacioComponent implements OnInit {

  start_date: string;
  stage: number;
  event_title:string;
  event_date;
  event_start;
  event_end;
  event_event_allDay:boolean = false;
  event_mentor;
  event_edit:boolean = false;
  show_calendar;
  notSupported = false;

  listaEventos = [
    {
      title: 'Espacio Reservado',
      start: '2020-12-03 10:00',
      end: '2020-12-03 12:00',
      allDay: false
    },
    {
      title: 'Espacio Reservado',
      start: '2020-12-04 12:00',
      end: '2020-12-04 14:00',
      allDay: false
    },
    {
      title: 'Espacio Reservado',
      start: '2020-12-01 11:00',
      end: '2020-12-01 18:00',
      allDay: false
    }
  ];

  calendarPlugins = [dayGridPlugin, timeGrigPlugin, interactionPlugin, listView];

  barChartOptions: ChartOptions = {
    responsive: true,
    responsiveAnimationDuration: 2,
    scales: {
      xAxes: [{}], yAxes: [
        {
          ticks: {
            min: 0,
          }
        }]
    },
  };
  barChartOptions1: ChartOptions = {
    responsive: true,
    responsiveAnimationDuration: 2,
    scales: {
      xAxes: [{}], yAxes: [
        {
          ticks: {
            min: 0,
            stepSize: 1,
          }
        }]
    },
  };
  barChartOptions10: ChartOptions = {
    responsive: true,
    responsiveAnimationDuration: 2,
    scales: {
      xAxes: [{}], yAxes: [
        {
          ticks: {
            min: 0,
            stepSize: 10,
          }
        }]
    },
  };
  barChartLabels: Label[] = ['9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];
  barChartType: ChartType = 'bar';
  barChartLegend = true;
  barChartPlugins = [];

  barChartColor: Color[] = [
    { backgroundColor: '#B9DDFD' },
  ]

  barChartData: ChartDataSets[] = [
    { data: [0, 10, 2, 5, 7, 3, 7, 10, 3, 5], label: 'Computadoras ocupadas' },
  ];

  barChartData1: ChartDataSets[] = [
    { data: [10, 50, 50, 35, 27, 12, 30, 5, 0, 20], label: 'Salas ocupadas' },
  ];

  barChartData2: ChartDataSets[] = [
    { data: [0, 50, 35, 27, 12, 30, 5, 20, 10, 50], label: 'Sillas ocupadas' },
  ];

  barChartData3: ChartDataSets[] = [
    { data: [1, 2, 0, 3, 3, 1, 1, 1, 2, 3], label: 'Oficinas ocupadas' },
  ];

  barChartData4: ChartDataSets[] = [
    { data: [1, 1, 0, 0, 1, 1, 0, 0, 0, 0], label: 'Salas ocupadas' },
  ];

  constructor() {
    this.stage = 1
    this.start_date = moment(new Date()).format('YYYY-MM-DD');
  }

  ngOnInit() {
  }event_allDay

  filter() {
    console.log(this.start_date);
    //to-do crear servicio que obtenga la data desde el api
  }

  reserve() {
    console.log('reserving....')
    this.stage = 2
    this.show_calendar=true
  }

  back() {
    this.stage = 1
  }

  select(event) {
    this.event_edit = true;
    this.event_title = event.event.title;
    this.event_date = moment(event.event.start).format('YYYY-MM-DD');
    this.event_start = moment(event.event.start).format('HH:mm');
    this.event_end = moment(event.event.end).format('HH:mm');
    this.event_mentor = 2;
  }

  addEvent() {
    this.listaEventos.push(
      { 
        title: 'Espacio Reservado',
        start: this.event_date + ' ' + this.event_start,
        end: this.event_date + ' ' + this.event_end,
        allDay: false
      }
    )
    Swal.fire("Reserva Ok!", "Reservaste el espacio con exito!", "success")
    this.stage=1
    this.clearModal();
  }

  updateEvent(){
    //todo: encontrar en el arreglo y editar
    this.clearModal();
  }

  clearModal(){
    this.event_title = '';
    this.event_date = '';
    this.event_start = '';
    this.event_end = '';
    this.event_mentor = '';  
  }

}
