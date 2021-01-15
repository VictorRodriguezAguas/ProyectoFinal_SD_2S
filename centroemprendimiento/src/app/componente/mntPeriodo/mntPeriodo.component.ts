import { AfterViewInit, Component, EventEmitter, Input, OnDestroy, OnInit, Output, ViewChild } from '@angular/core';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs';
import { General } from 'src/app/estructuras/General';
import { CatalogoService } from 'src/app/servicio/catalogo.service';
import { MensajeService } from 'src/app/servicio/Mensaje.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-mntPeriodo',
  templateUrl: './mntPeriodo.component.html',
  styleUrls: ['./mntPeriodo.component.css']
})
export class MntPeriodoComponent implements OnInit, AfterViewInit, OnDestroy {

  @Input() titulo;  
  @Input() periodos: Periodo[];
  @Output() grabar=new EventEmitter<any>();
  @Output() eliminar=new EventEmitter<any>();
  @Output() getPeriodos=new EventEmitter<any>();

  txtContrato = null;
  txtFechaInicio = General.getFechaActual();
  txtFechaFin = General.getFechaActual();
  @Input() maxHorasSemana = null;
  @Input() maxHorasMes = null;
  @Input() idSubPrograma = 1;

  @ViewChild(DataTableDirective, { static: false }) dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject();

  listaEdiciones:any[];

  @ViewChild('periodoModal', { static: false }) private periodoModal;

  constructor(private mensajeService: MensajeService,
    private catalogoService: CatalogoService) { }

  periodo: Periodo;
  ngOnInit() {
    this.catalogoService.getEdiciones(this.idSubPrograma).subscribe(data=>{
      if(data.codigo == '1'){
        this.listaEdiciones = data.data;
      }
    });
  }

  guardarPeriodo(){
    this.grabar.emit(this.periodo);
  }

  eliminarPeriodo(i: number): void {
    this.mensajeService.confirmAlert('¿Desea eliminar el periodo?', 'Una vez eliminado no se podra recuperar la informacion').then((willDelete) => {
        if (willDelete.dismiss) {
        } else {
          this.periodos.splice(i, 1);
          this.rerender();
          this.getPeriodos.emit(this.periodos);
          this.eliminar.emit(this.periodo);
        }
      });
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

  ngAfterViewInit(): void {
    this.dtTrigger.next();
  }

  ngOnDestroy(): void {
    // Do not forget to unsubscribe the event
    this.dtTrigger.unsubscribe();
  }

  agregarPeriodo(): void {
    if (Date.parse(this.periodo.fecha_inicio) > Date.parse(this.periodo.fecha_fin)) {
      this.mensajeService.alertError(null, 'La fecha de inicio no puede ser menor a la fecha de fin');
    } else {
      let grabar = true;
      this.periodos.forEach(item=>{
        let fInicio = Date.parse(this.periodo.fecha_inicio);
        let fFin = Date.parse(this.periodo.fecha_fin);
        let fInicioAux = Date.parse(item.fecha_inicio);
        let fFinAux = Date.parse(item.fecha_fin);
        //console.log('Validacion 1');
        //console.log(fInicio + '>=' + fInicioAux + ' && ' + fInicio + '<='  + fFinAux + '=' + (fInicio >= fInicioAux && fInicio <= fFinAux) )
        if(fInicio >= fInicioAux && fInicio <= fFinAux){
          grabar = false;
        }
        //console.log('Validacion 2');
        //console.log(fInicio + '<=' + fInicioAux + ' && ' + fFin + '>='  + fInicioAux + '=' + (fInicio <= fInicioAux && fFin >= fInicioAux) )
        if(fInicio <= fInicioAux && fFin >= fInicioAux){
          grabar = false;
        }
      });
      if(!grabar){
        this.mensajeService.alertError(null, 'Las fechas agregadas se cruzan con las fechas de periodos ya ingresados');
        return;
      }

      /*let periodo: Periodo = {
        fecha_inicio: this.txtFechaInicio, fecha_fin: this.txtFechaFin, contrato: this.txtContrato, max_horas_mes: this.maxHorasMes, max_horas_semana: this.maxHorasSemana
      }*/
      // Si pasa el primer filtro validar que no sea un horario repetido o que se cruze
      this.periodos.push(this.periodo);
      this.getPeriodos.emit(this.periodos);
      this.grabar.emit(this.periodo);
      this.periodoModal.hide();
      this.rerender();
    }
  }

  nuevoPeriodo(){
    this.periodo = {contrato: null,fecha_fin: General.getFechaActual(), fecha_inicio: General.getFechaActual(), max_horas_semana: this.maxHorasSemana, max_horas_mes: this.maxHorasMes};
    this.txtContrato = null;
    this.txtFechaInicio = General.getFechaActual();
    this.txtFechaFin = General.getFechaActual();
    this.periodoModal.show();
  }
}

export interface Periodo{
  fecha_inicio:string;
  fecha_fin:string;
  contrato:string;
  max_horas_semana?:number;
  max_horas_mes?:number;
  id_edicion?:number;
}
