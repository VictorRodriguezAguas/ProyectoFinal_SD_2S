import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';

@Component({
  selector: 'app-inputIncrement',
  templateUrl: './inputIncrement.component.html',
  styleUrls: ['./inputIncrement.component.scss']
})
export class InputIncrementComponent implements OnInit {

  @Output() getValue=new EventEmitter<number>()
  @Input() value:number=0;
  @Input() maxValue:number;
  @Input() minValue:number;

  constructor() { }

  ngOnInit() {
  }

  aumentar(){
    if(this.maxValue){
      if(!(this.maxValue < this.value + 1)){
        this.value++;
        this.getValue.emit(this.value);
      }
    }
  }

  reducir(){
    if(this.minValue){
      if(!(this.minValue > this.value - 1)){
        this.value--;
        this.getValue.emit(this.value);
      }
    }
  }
}
