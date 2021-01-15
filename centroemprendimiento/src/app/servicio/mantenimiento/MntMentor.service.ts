import { Injectable } from '@angular/core';
import { HttpHeaders, HttpClient, HttpParams, HttpRequest } from '@angular/common/http';
import { Respuesta } from 'src/app/estructuras/respuesta';
import { environment } from 'src/environments/environment';
import { HorarioMentor, Mentor, PeriodoMentor } from 'src/app/estructuras/mentor';

@Injectable({
  providedIn: 'root'
})
export class MntMentorService {

  constructor(private http: HttpClient) { }

  httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/x-www-form-urlencoded' })
  };

  getMentores() {
    const body = new HttpParams();
    //.set('estado',estado)
    return this.http.post<Respuesta>(environment.base_api_url + 'mantenimiento/getMentores', body.toString(), this.httpOptions);
  }

  getMentoresAllInfo() {
    const body = new HttpParams();
    //.set('estado',estado)
    return this.http.post<Respuesta>(environment.base_api_url + 'mantenimiento/getMentoresAllInfo', body.toString(), this.httpOptions);
  }

  getMentorPersona(id_persona) {
    const body = new HttpParams()
    .set('id_persona',id_persona);
    return this.http.post<Respuesta>(environment.base_api_url + 'mantenimiento/getMentorPersona', body.toString(), this.httpOptions);
  }

  getMentor(id_mentor) {
    const body = new HttpParams()
    .set('id_mentor',id_mentor);
    return this.http.post<Respuesta>(environment.base_api_url + 'mantenimiento/getMentor', body.toString(), this.httpOptions);
  }

  grabarHorarios(horarios:HorarioMentor[], id_mentor) {
    const body = new HttpParams()
    .set('id_mentor',id_mentor)
    .set('horarios',JSON.stringify(horarios));
    return this.http.post<Respuesta>(environment.base_api_url + 'mantenimiento/grabarHorariosMentor', body.toString(), this.httpOptions);
  }

  grabarMentor(mentor:Mentor, crearUsuario) {
    const body = new HttpParams()
    .set('datos',JSON.stringify(mentor))
    .set('crearUsuario',crearUsuario);
    return this.http.post<Respuesta>(environment.base_api_url + 'mantenimiento/grabarMentor', body.toString(), this.httpOptions);
  }

  grabarPeriodoMentor(periodo:PeriodoMentor) {
    const body = new HttpParams()
    .set('datos',JSON.stringify(periodo));
    return this.http.post<Respuesta>(environment.base_api_url + 'mantenimiento/grabarPeriodoMentor', body.toString(), this.httpOptions);
  }
}
