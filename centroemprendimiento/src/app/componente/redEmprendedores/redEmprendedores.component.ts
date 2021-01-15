import { Component, OnInit } from '@angular/core';
import { CatalogoService } from 'src/app/servicio/catalogo.service';
import { Usuario } from 'src/app/estructuras/usuario';
import { MensajeService } from 'src/app/servicio/Mensaje.service';
import { General } from 'src/app/estructuras/General';

@Component({
  selector: 'app-redEmprendedores',
  templateUrl: './redEmprendedores.component.html',
  styleUrls: ['./redEmprendedores.component.css']
})
export class RedEmprendedoresComponent implements OnInit {

  listaEmprendedores:Usuario[] = [];
  inicial="";

  constructor(private catalogoService: CatalogoService,
    private mensajeService: MensajeService) { }

  ngOnInit() {
    this.consultarEmprendedores('A', '');
  }

  consultarEmprendedores(inicial: string, nombre: string): void{
    this.inicial = inicial;
    let param = {inicial:inicial, nombre:nombre};
    this.catalogoService.post('emprendedor/emprendedoresCE', param).subscribe(data => {
      if(data.data instanceof Array){
        this.listaEmprendedores = data.data as Usuario[];
      }
    });
  }

  solicitarContacto(){
    this.mensajeService.alertEpico('¡Ups!', 'Esta opción estará habilitada muy pronto.');
  }

  public pictNotLoading(event) { 
    General.pictNotLoading(event);
  }
}
