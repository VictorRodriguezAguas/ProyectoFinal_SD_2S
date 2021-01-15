/* tslint:disable:no-unused-variable */
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { DebugElement } from '@angular/core';

import { AsignarMentorComponent } from './asignarMentor.component';

describe('AsignarMentorComponent', () => {
  let component: AsignarMentorComponent;
  let fixture: ComponentFixture<AsignarMentorComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AsignarMentorComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AsignarMentorComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
