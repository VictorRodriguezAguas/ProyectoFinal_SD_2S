import { Component, OnInit, Output, EventEmitter, Input, AfterViewInit } from '@angular/core';
import { MouseEvent } from '@agm/core';
import { ApiGoogleService } from '../../servicio/ApiGoogle.service';

@Component({
  selector: 'app-agm',
  templateUrl: './agm.component.html',
  styleUrls: ['./agm.component.scss']
})
export class AgmComponent implements OnInit, AfterViewInit {
  @Input() lat: number;
  @Input() lng: number;
  @Input() zoom: number;
  mapTypeId: string;
  location: boolean;
  markers: marker[];
  @Input() address: string;

  @Output() gerAddress = new EventEmitter<any>();


  constructor(private apiGoogleService: ApiGoogleService) {
    this.lat = -2.0543707;
    this.lng = -80.3824874;
    this.zoom = 10;
    this.mapTypeId = 'hybrid'
    this.location = false

  }

  exportData() {
    const obj = {
      lat: this.lat,
      lng: this.lng,
      address: this.address,
    }
    this.gerAddress.emit(obj);
  }

  ngOnInit() {
    /*if(!this.lat && !this.lng && !this.address){
      this.getCurrentPosition();
    }*/
    this.getCurrentPosition();
  }

  ngAfterViewInit(){
    setTimeout(() => {
      if(!this.lng && !this.lat && this.address){
        //this.search();
      }
    }, 500);
  }

  search() {
    this.getGeocoding(this.address)
  }

  getCurrentPosition() {
    navigator.geolocation.getCurrentPosition(position => {
      this.lat = position.coords.latitude;
      this.lng = position.coords.longitude;
      this.zoom = 14
      this.location = true
    })
  }

  markerDragEnd($event: MouseEvent) {
    this.lat = $event.coords.lat
    this.lng = $event.coords.lng
    this.getReverseGeocoding(this.lat, this.lng)
  }

  getGeocoding(address) {
    console.log(address)
    this.apiGoogleService.getGeocoding(address)
      .then(geocode => {
        console.log(geocode)
        if (geocode.status == 'OK') {
          console.log('statusOK', geocode.results[0].geometry.location)
          const pos = geocode.results[0].geometry.location;
          this.lat = pos.lat;
          this.lng = pos.lng;
          this.exportData();
        } else {
          console.log('Algo salió mal')
        }
      })
      .catch(error => console.log(error))
  }

  getReverseGeocoding(latitude, longitude) {
    console.log(latitude, longitude)
    const pos = { lat: latitude, lng: longitude }
    this.apiGoogleService.getReverseGeocoding(pos)
      .then(geocode => {
        console.log(geocode)
        if (geocode.status == 'OK') {
          console.log('statusOK', geocode.results[0].formatted_address)
          this.address = geocode.results[0].formatted_address;
          this.exportData();
        } else {
          console.log('Algo salió mal')
        }
      })
      .catch(error => console.log(error))
  }

}

interface marker {
  lat: number;
  lng: number;
  label?: string;
  draggable: boolean;
}
