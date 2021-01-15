import { Component, Input, OnInit, Output, EventEmitter, ViewChild, AfterViewInit } from '@angular/core';
import { General } from 'src/app/estructuras/General';
import { CatalogoService } from 'src/app/servicio/catalogo.service';

@Component({
  selector: 'app-selectMentor',
  templateUrl: './selectMentor.component.html',
  styleUrls: ['./selectMentor.component.scss']
})
export class SelectMentorComponent implements OnInit, AfterViewInit {

  @Input() id_eje_mentoria;
  @Input() tema_mentoria;
  @Output() selectMentor=new EventEmitter<any>();
  @Output() cancelar=new EventEmitter<any>();

  @ViewChild('modalMentor', { static: false }) private modalMentor;

  listaMentores=[];

  constructor(private catalogoService: CatalogoService) { }

  ngOnInit() {
    this.consultarMentores();
  }

  ngAfterViewInit(){
    setTimeout(() => {
      this.modalMentor.show();
    }, 100);
  }

  consultarMentores(){
    this.listaMentores=[];
    this.catalogoService.getListaMentores(this.id_eje_mentoria).subscribe(data=>{
      if(data.codigo == '1'){
        this.listaMentores = data.data;
      }
    });
  }

  seleccionarMentor(mentor){
    this.modalMentor.hide();
    this.selectMentor.emit(mentor);
  }

  cancelarMentor(){
    this.modalMentor.hide();
    this.cancelar.emit();
  }

  getFoto(url_foto){
    return General.getFoto(url_foto);
  }

  pictNotLoading(event){
    General.pictNotLoading(event);
  }
}
