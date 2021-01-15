import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Respuesta } from '../estructuras/respuesta';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class AgendaService {

  constructor(private http: HttpClient) { }

  httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/x-www-form-urlencoded' })
  };

  getHorarioDisponibilidadAT(id_asistente_tecnico?) {
    id_asistente_tecnico = id_asistente_tecnico ? id_asistente_tecnico : '';
    const body = new HttpParams().set('id_asistente_tecnico',id_asistente_tecnico);
    return this.http.post<Respuesta>(environment.base_api_url + 'agenda/getHorarioAT', body.toString(), this.httpOptions);
  }

  getHorarioDisponibilidadMentor(id_mentor) {
    const body = new HttpParams()
    .set('id_mentor',id_mentor);
    return this.http.post<Respuesta>(environment.base_api_url + 'agenda/getHorarioMentor', body.toString(), this.httpOptions);
  }

  grabarAgendaAT(agenda, id_asistente_tecnico?) {
    id_asistente_tecnico = id_asistente_tecnico ? id_asistente_tecnico : '';
    const body = new HttpParams()
    .set('id_asistente_tecnico',id_asistente_tecnico)
    .set('datos',JSON.stringify(agenda));
    return this.http.post<Respuesta>(environment.base_api_url + 'agenda/insertarAgendaAT', body.toString(), this.httpOptions);
  }

  grabarAgendaMentor(agenda, id_mentor) {
    const body = new HttpParams()
    .set('id_mentor',id_mentor)
    .set('datos',JSON.stringify(agenda));
    return this.http.post<Respuesta>(environment.base_api_url + 'agenda/insertarAgendaMentor', body.toString(), this.httpOptions);
  }

  grabarAgendaEvento(agenda) {
    const body = new HttpParams()
    .set('datos',JSON.stringify(agenda));
    return this.http.post<Respuesta>(environment.base_api_url + 'agenda/insertarAgendaEvento', body.toString(), this.httpOptions);
  }

  cancelarAgenda(id_agenda, id_motivo, observacion) {
    const body = new HttpParams()
    .set('id_agenda',id_agenda)
    .set('id_motivo',id_motivo)
    .set('observacion',observacion);
    return this.http.post<Respuesta>(environment.base_api_url + 'agenda/cancelar', body.toString(), this.httpOptions);
  }

  getAgenda(id_agenda?, id_actividad_inscripcion?) {
    let body = new HttpParams();
    if(id_agenda){
      body = body.set('id_agenda',id_agenda);
    }
    if(id_actividad_inscripcion){
      body = body.set('id_actividad_inscripcion',id_actividad_inscripcion);
    }
    return this.http.post<Respuesta>(environment.base_api_url + 'agenda/getAgenda', body.toString(), this.httpOptions);
  }

  getAgendaxPersona2(id_persona, tipo) {
    let body = new HttpParams()
    .set('id_persona',id_persona)
    .set('tipo',tipo);
    return this.http.post<Respuesta>(environment.base_api_url + 'agenda/getAgendaPersona2', body.toString(), this.httpOptions);
  }
}
