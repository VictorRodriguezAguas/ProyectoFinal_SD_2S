/* tslint:disable:no-unused-variable */
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { DebugElement } from '@angular/core';

import { ListToDoComponent } from './listToDo.component';

describe('ListToDoComponent', () => {
  let component: ListToDoComponent;
  let fixture: ComponentFixture<ListToDoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ListToDoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ListToDoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
