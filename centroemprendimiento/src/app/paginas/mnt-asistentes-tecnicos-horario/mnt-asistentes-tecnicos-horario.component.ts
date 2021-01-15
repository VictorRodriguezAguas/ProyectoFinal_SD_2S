import { Component, OnInit} from '@angular/core';
import { ActivatedRoute } from '@angular/router';

import { AsistentetecnicoService } from 'src/app/servicio/Asistentetecnico.service';
import { HorarioAt } from 'src/app/interfaces/horarioat';
import { MensajeService } from 'src/app/servicio/Mensaje.service';

@Component({
  selector: 'app-mnt-asistentes-tecnicos-horario',
  templateUrl: './mnt-asistentes-tecnicos-horario.component.html',
  styleUrls: ['./mnt-asistentes-tecnicos-horario.component.scss'],
})
export class MntAsistentesTecnicosHorarioComponent implements OnInit {
  
  horarioAT: HorarioAt[];
  nombreAsistenteTecnico = '';
  idAsistenteTecnico: number;

  constructor(
    private route: ActivatedRoute,
    private asistenteTecnicoService: AsistentetecnicoService,
    private mensajeService: MensajeService
  ) { }

  ngOnInit() {
    this.getAsistenteTecnico();
  }

  getAsistenteTecnico(): void {
    this.idAsistenteTecnico = +this.route.snapshot.paramMap.get('id_asistencia_tecnica');
    this.nombreAsistenteTecnico = this.route.snapshot.paramMap.get('nombre').toString();
    this.getAsistenteTecnicoHorario(this.idAsistenteTecnico)
  }

  getAsistenteTecnicoHorario(idAsistenciaTecnica: number): void {
    this.asistenteTecnicoService.getHorarioAT(idAsistenciaTecnica)
    .subscribe(
      horarioAT => {
        this.horarioAT = horarioAT.data
      }
    )
  }

  guardarHorario(): void {
    this.asistenteTecnicoService.addHorario(this.horarioAT, this.idAsistenteTecnico)
    .subscribe(result =>{
      if(result.codigo == '1') {
        this.mensajeService.alertOK();
      }
      else {
        this.mensajeService.alertError(null, result.mensaje);
      }
    });
  }

  grabar(horario){
    this.horarioAT = horario;
    this.horarioAT.forEach(item=>{
      item.id_asistencia_tecnica = this.idAsistenteTecnico;
    });
    this.guardarHorario();
  }

}
