import { Component, Input, OnInit } from '@angular/core';

@Component({
  selector: 'app-redes_sociales',
  templateUrl: './redes_sociales.component.html',
  styleUrls: ['./redes_sociales.component.scss']
})
export class Redes_socialesComponent implements OnInit {

  @Input() redes_sociales:red_social[];

  constructor() { }

  ngOnInit() {
  }

  redSocial(red){
    if(red)
      window.open(red, '_blank');
  }

}

export interface red_social{
  nombre:string;
  red:string;
  icono?:string;
}
