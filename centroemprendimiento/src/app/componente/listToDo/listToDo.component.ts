import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-listToDo',
  templateUrl: './listToDo.component.html',
  styleUrls: ['./listToDo.component.css']
})
export class ListToDoComponent implements OnInit {

  constructor() { }

  public todoListMessage: string;
  public todo_list_message_error: boolean;

  @Input() lista:any[]=[];
  @Output() getLista=new EventEmitter<any>();
  @Input() titulo="Lista de items";
  @Input() editable=true;
  @Input() placeholder="Ingrese item";

  ngOnInit() {
  }

  addTodoList() {
    if(!this.editable){
      return;
    }
    if (this.todoListMessage === '' || this.todoListMessage === undefined) {
      this.todo_list_message_error = true;
    } else {
      this.lista.push(this.todoListMessage);
      this.getLista.emit(this.lista);
      this.todoListMessage = '';
    }
  }

  eliminarItem(i: number) {
    Swal.fire({
      title: '¿Desea eliminar este registro?',
      text: 'Una vez eliminado no se podra recuperar la información',
      type: 'warning',
      showCloseButton: true,
      showCancelButton: true
    }).then((willDelete) => {
      if (willDelete.value) {
        this.lista.splice(i, 1);
        this.getLista.emit(this.lista);
      }
    });
  }
}
