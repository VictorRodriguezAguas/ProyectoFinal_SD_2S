import { Component, Input, OnInit } from '@angular/core';
import { General } from 'src/app/estructuras/General';

@Component({
  selector: 'app-reunionDatos',
  templateUrl: './reunionDatos.component.html',
  styleUrls: ['./reunionDatos.component.scss']
})
export class ReunionDatosComponent implements OnInit {

  @Input() emprendedor;
  @Input() archivo;
  @Input() vista=1;
  
  constructor() { }

  ngOnInit() {
    console.log(this.emprendedor);
  }

  getDato(value) {
    return value ? value : 'Sin información';
  }

  public pictNotLoading(event) { General.pictNotLoading(event); }

}
