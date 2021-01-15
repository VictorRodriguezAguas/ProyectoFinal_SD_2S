import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpRequest, HttpParams } from '@angular/common/http';
import { environment } from 'src/environments/environment';
import { Respuesta } from '../estructuras/respuesta';
import { Observable, of } from 'rxjs';
import { tap } from 'rxjs/operators';
import { MensajeService } from './Mensaje.service';
import { Inscripcion, Actividad_inscripcion } from '../estructuras/inscripcion';
import { Usuario } from '../estructuras/usuario';
import { Router } from '@angular/router';
import { Mentoria } from '../interfaces/Mentoria';

@Injectable({
  providedIn: 'root'
})
export class ProgramaService {

  actividad_selecionada:Actividad_inscripcion;
  programa_selecionada:Inscripcion;

  constructor(private http: HttpClient, private mensajeService: MensajeService,
    private router: Router) { }

  httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/x-www-form-urlencoded' })
  };

  actualizarActividadForm(formData: FormData) {
    let httpOptions = {
      reportProgress: true
    };
    let req = new HttpRequest('POST', environment.base_api_url + 'programa/actualizarActividad', formData, httpOptions);
    return this.http.request<Respuesta>(req);
  }

  getProgramas(id_persona) {
    const body = new HttpParams().set('id_persona', id_persona);
    return this.http.post<Respuesta>(environment.base_api_url + 'programa/programasInscritos', body.toString(), this.httpOptions);
  }

  getActividadInscripcionXAgenda(id_agenda) {
    const body = new HttpParams().set('id_agenda', id_agenda);
    return this.http.post<Respuesta>(environment.base_api_url + 'programa/getActividadXidAgenda', body.toString(), this.httpOptions);
  }

  getProgramaInscrito(id_sub_programa, fase, id_persona?) {
    let body = new HttpParams().set('id_sub_programa', id_sub_programa);
    if(fase){
      body = body.set('fase',fase);
    }
    if(id_persona){
      body = body.set('id_persona',id_persona);
    }
    return this.http.post<Respuesta>(environment.base_api_url + 'programa/getProgramaInscrito', body.toString(), this.httpOptions);
  }

  finalizar_actividad(redirect?):Observable<any>{
    return this._finalizar_actividad().pipe(
      tap(data=>{
        if(data.codigo == '1'){
          this.mensajeService.alertEpico('¡Felicitaciones!','Tu actividad fue culminada con éxito').then((result) => {
            if (result.value) {
              if(!redirect)
                window.location.reload();
              else
                this.router.navigate([redirect]);
            }
          });
        }else{
          this.mensajeService.alertError(null,data.mensaje);
        }
      })      
    );
  }

  private _finalizar_actividad(){
    this.actividad_selecionada.estado_actividad_inscripcion="AP";
    return this.actualizar_actividad();
  }

  actualizar_actividad(actividad?){
    return this._actualizar_actividad(actividad);
  }

  private _actualizar_actividad(actividad?){
    if(!actividad){
      actividad = this.actividad_selecionada;
    }
    actividad.id_usuario_mod = Usuario.getUser().id_usuario;
    const body = new HttpParams().set('datos', JSON.stringify(actividad));
    return this.http.post<Respuesta>(environment.base_api_url + 'programa/actualizarActividad', body.toString(), this.httpOptions);
  }

  actualizar_inscripcion(){
    const body = new HttpParams()
      .set('datos', JSON.stringify(this.programa_selecionada.sub_programa));
    return this.http.post<Respuesta>(environment.base_api_url + 'programa/actualizarInscripcion', body.toString(), this.httpOptions);
  }  

  public aprobarActividades(actividades:[]){
    const body = new HttpParams().set('datos', JSON.stringify(actividades));
    return this.http.post<Respuesta>(environment.base_api_url + 'programa/aprobarActividades', body.toString(), this.httpOptions);
  }

  public cambiarFase(id_inscripcion, fase_anterior, fase_nueva){
    const body = new HttpParams()
    .set('id_inscripcion', id_inscripcion)
    .set('fase_anterior', fase_anterior)
    .set('fase_nueva', fase_nueva);
    return this.http.post<Respuesta>(environment.base_api_url + 'programa/cambiarFase', body.toString(), this.httpOptions);
  }

  public aprobarEtapa(id_inscripcion, fase_anterior, fase_nueva){
    const body = new HttpParams()
    .set('id_inscripcion', id_inscripcion)
    .set('fase_anterior', fase_anterior)
    .set('fase_nueva', fase_nueva);
    return this.http.post<Respuesta>(environment.base_api_url + 'programa/aprobarEtapa', body.toString(), this.httpOptions);
  }

  public revertirActividad(actividad, id_actividad_inscripcion?, estado?){
    let body;
    if(!actividad && (!id_actividad_inscripcion || !estado)){
      return of({codigo:"0", mensaje:'Faltan paramentro'});
    }
    if(actividad){
      body = new HttpParams()
      .set('datos', JSON.stringify(actividad));
    }else{
      body = new HttpParams()
      .set('id_actividad_inscripcion', id_actividad_inscripcion)
      .set('estado', estado);
    }
    //return this.http.post<Respuesta>(environment.base_api_url + 'programa/revertirActividad', body.toString(), this.httpOptions);
    return this._revertirActividad(body);
  }

  private _revertirActividad(body){
    return this.http.post<Respuesta>(environment.base_api_url + 'programa/revertirActividad', body.toString(), this.httpOptions);
  }

  public asignarActividades(actividades:any[], id_inscripcion, id_reunion?){
    let body = new HttpParams()
    .set('datos', JSON.stringify(actividades))
    .set('id_inscripcion', id_inscripcion);
    if(id_reunion){
      body = body.set('id_reunion', id_reunion);
    }
    return this.http.post<Respuesta>(environment.base_api_url + 'programa/asignarActividades', body.toString(), this.httpOptions);
  }

  public actualizarMensajeAprobacionEtapa(id_inscripcion, fase){
    const body = new HttpParams()
    .set('fase', fase)
    .set('id_inscripcion', id_inscripcion);
    return this.http.post<Respuesta>(environment.base_api_url + 'programa/actualizarMensajeAprobacion', body.toString(), this.httpOptions);
  }

  public actualizarNewOrdenActividades(id_etapa){
    const body = new HttpParams()
    .set('id_etapa', id_etapa);
    return this.http.post<Respuesta>(environment.base_api_url + 'programa/actualizarNewOrdenActividades', body.toString(), this.httpOptions);
  }

  public asignarMentorias(id_inscripcion, id_inscripcion_etapa, mentorias:Mentoria[], id_reunion?){
    let body = new HttpParams()
    .set('datos', JSON.stringify(mentorias))
    .set('id_inscripcion_etapa', id_inscripcion_etapa)
    .set('id_inscripcion', id_inscripcion);
    if(id_reunion){
      body = body.set('id_reunion', id_reunion);
    }
    return this.http.post<Respuesta>(environment.base_api_url + 'programa/asignarMentoria', body.toString(), this.httpOptions);
  }

  public getMentoriasPendientes(id_inscripcion, id_inscripcion_etapa){
    let body = new HttpParams()
    .set('id_inscripcion_etapa', id_inscripcion_etapa)
    .set('id_inscripcion', id_inscripcion);
    return this.http.post<Respuesta>(environment.base_api_url + 'programa/getMentoriasPendientes', body.toString(), this.httpOptions);
  }

  public cambiarMentores(mentorias:Mentoria[]){
    let body = new HttpParams()
    .set('datos', JSON.stringify(mentorias));
    return this.http.post<Respuesta>(environment.base_api_url + 'programa/cambiarMentor', body.toString(), this.httpOptions);
  }

  public getInscripcionEtapa(id_inscripcion_etapa, id_actividad_inscripcion){
    const body = new HttpParams()
    .set('id_inscripcion_etapa', id_inscripcion_etapa)
    .set('id_actividad_inscripcion', id_actividad_inscripcion);
    return this.http.post<Respuesta>(environment.base_api_url + 'programa/getInscripcionEtapa', body.toString(), this.httpOptions);
  }

  public getAsistenteTecnicoAsignado(id_inscripcion){
    const body = new HttpParams()
    .set('id_inscripcion', id_inscripcion);
    return this.http.post<Respuesta>(environment.base_api_url + 'programa/getAsistenteTecnicoAsignado', body.toString(), this.httpOptions);
  }

  armarArbolActividades(lista, padre, attrChilds?){
    if(!attrChilds){
      attrChilds = "child";
    }
    lista.forEach(item => {
      item.id = item.id_actividad_etapa;
      item.id_padre = item.id_actividad_padre;
      if(item.is_agregada_manual == 'SI'){
        item.id = item.id_actividad_inscripcion;
        item.id_padre = item.id_actividad_inscripcion_padre;
      }
    });
    //let listaAux = this._armarArbolActividadesAdicionales(lista, null, attrChilds);
    //let actividades = this._armarArbolActividades(lista, null, attrChilds);
    /*listaAux.forEach(element => {
      actividades.push(element);
    });*/
    let actividades = this._armarArbolActividadesNew(lista, null, attrChilds);
    return actividades;
  }

  _armarArbolActividadesNew(lista, padre, attrChilds){
    let listaNew:any[] = [];
    padre = padre ? padre : {id_actividad_etapa: null, id_actividad_inscripcion_padre: null, id: null, id_padre:null};
    lista.forEach(item => {
      //if(item.is_agregada_manual == 'NO' || !item.is_agregada_manual) {
        if(item.id){
          if(item.id_padre == padre.id || (item.id_padre == padre.id_actividad_inscripcion && padre.id_actividad_inscripcion) ){
            item.isCollapsed=true;
            item[attrChilds] = this._armarArbolActividadesNew(lista, item, attrChilds);
            item.is_padre = false;
            if(item[attrChilds].length > 0){
              item.is_padre = true;
            }
            listaNew.push(item);
          }
        }else{
          if(!item.id_padre){
            item.isCollapsed=true;
            item[attrChilds] = this._armarArbolActividadesNew(lista, item, attrChilds);
            if(item[attrChilds].length > 0){
              item.is_padre = true;
            }
            listaNew.push(item);
          }
        }
     // }
    });
    return listaNew;
  }

  _armarArbolActividades(lista, padre, attrChilds){
    let listaNew:any[] = [];
    padre = padre ? padre : {id_actividad_etapa: null, id_actividad_inscripcion_padre: null};
    lista.forEach(item => {
      if(item.is_agregada_manual == 'NO' || !item.is_agregada_manual) {     
        if(item.id_actividad_etapa){
          if(item.id_actividad_padre == padre.id_actividad_etapa){
            item.isCollapsed=true;
            item[attrChilds] = this._armarArbolActividades(lista, item, attrChilds);
            item.is_padre = false;
            if(item[attrChilds].length > 0){
              item.is_padre = true;
            }
            listaNew.push(item);
          }
        }else{
          if(!item.id_actividad_padre){
            item.isCollapsed=true;
            item[attrChilds] = this._armarArbolActividades(lista, item, attrChilds);
            if(item[attrChilds].length > 0){
              item.is_padre = true;
            }
            listaNew.push(item);
          }
        }
      }
    });
    return listaNew;
  }

  _armarArbolActividadesAdicionales(lista, padre, attrChilds){
    let listaNew:any[] = [];
    padre = padre ? padre : {id_actividad_etapa: null, id_actividad_inscripcion_padre: null, id_actividad_inscripcion: null};
    lista.forEach(item => {
      if(item.is_agregada_manual == 'SI') {     
        if(item.id_actividad_etapa){
          if(item.id_actividad_inscripcion_padre == padre.id_actividad_inscripcion){
            item.isCollapsed=true;
            item[attrChilds] = this._armarArbolActividadesAdicionales(lista, item, attrChilds);
            item.is_padre = false;
            if(item[attrChilds].length > 0){
              item.is_padre = true;
            }
            listaNew.push(item);
          }
        }else{
          if(!item.id_actividad_inscripcion_padre){
            item.isCollapsed=true;
            item[attrChilds] = this._armarArbolActividadesAdicionales(lista, item, attrChilds);
            if(item[attrChilds].length > 0){
              item.is_padre = true;
            }
            listaNew.push(item);
          }
        }
      }
    });
    return listaNew;
  }

  _armarArbolActividadesAux(lista, padre, attrChilds){
    let listaNew:any[] = [];
    padre = padre ? padre : {id_actividad_etapa: null, id_actividad_inscripcion_padre: null};
    lista.forEach(item => {
      if(item.id_actividad_etapa){
        if(item.id_actividad_padre == padre.id_actividad_etapa){
          item.isCollapsed=true;
          item[attrChilds] = this._armarArbolActividadesAux(lista, item, attrChilds);
          item.is_padre = false;
          if(item[attrChilds].length > 0){
            item.is_padre = true;
          }
          listaNew.push(item);
        }
      }else{
        if(!item.id_actividad_padre){
          item.isCollapsed=true;
          item[attrChilds] = this._armarArbolActividadesAux(lista, item, attrChilds);
          if(item[attrChilds].length > 0){
            item.is_padre = true;
          }
          listaNew.push(item);
        }
      }
    });
    return listaNew;
  }
}
