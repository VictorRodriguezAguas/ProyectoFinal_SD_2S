import { AfterViewInit, Component, Input, OnInit, Output, ViewChild, EventEmitter } from '@angular/core';
import { ExportService } from 'src/app/servicio/export.service';

@Component({
  selector: 'app-exportar',
  templateUrl: './exportar.component.html',
  styleUrls: ['./exportar.component.scss']
})
export class ExportarComponent implements OnInit, AfterViewInit {

  @Input() lista = [];
  @Input() title = "descarga";

  @ViewChild('camposModal', { static: false }) private camposModal;

  todos=true;
  camposDinamicos=true;
  @Input() campos:campo[]=[];
  @Input() hiddeButton = false;

  @Output() salir=new EventEmitter<any>();

  constructor(private exportService: ExportService) { }

  ngOnInit() {
    if(this.campos.length>0){
      this.camposDinamicos = false;
      this.campos.sort((a, b) => (a.campo < b.campo ? -1 : 1));
    }
  }

  ngAfterViewInit(): void {
    if(this.hiddeButton){
      setTimeout(() => {
        this.descargar();
      }, 500);
    }
  }

  getCampos(){
    if(this.camposDinamicos){
      this.todos=true;
      this.campos = [];
      if(this.lista.length > 0){
        for (let campo in this.lista[0]) {
          this.campos.push({campo: campo, selected:true});
        }
        this.campos.sort((a, b) => (a.campo < b.campo ? -1 : 1));
      }
    }
  }

  descargar(){
    this.getCampos();
    this.camposModal.show();
  }

  _descargar(){
    if(this.todos){
      this.exportService.exportAsExcelFile(this.lista, this.title); 
    }else{
      let listaNueva = [];
      this.lista.forEach(element => {
        let item = {};
        this.campos.forEach(campo => {
          if(campo.selected){
            item[campo.campo]=this.getValue(element[campo.campo]);
          }
        });
        listaNueva.push(item);
      });
      this.exportService.exportAsExcelFile(listaNueva, this.title); 
    }
  }

  getValue(item){
    let valor = "";
    if(Array.isArray(item)){
      item.forEach(element => {
        valor += valor == '' ? this.getValue(element) : (';' + this.getValue(element));
      });
      return valor;
    }
    if(typeof item == 'object'){
      for (let campo in item) {
        valor += valor == '' ? item[campo] : '|' + item[campo];
      }
    }else{
      valor = item;
    }
    return valor;
  }

  select(){
    const descAllSelected = this.campos.length > 0 && this.campos.every(child => {
      return child.selected;
    });
    this.todos = descAllSelected;
  }

  selectAll(){
    this.campos.forEach(element => {
      element.selected = this.todos;
    });
  }

  _salir(){
    this.camposModal.hide();
    this.salir.emit();
  }
}

export interface campo{
  campo: string;
  selected: boolean;
}
