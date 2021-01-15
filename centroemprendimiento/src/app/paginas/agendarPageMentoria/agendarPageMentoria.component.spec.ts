/* tslint:disable:no-unused-variable */
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { DebugElement } from '@angular/core';

import { AgendarPageMentoriaComponent } from './agendarPageMentoria.component';

describe('AgendarPageMentoriaComponent', () => {
  let component: AgendarPageMentoriaComponent;
  let fixture: ComponentFixture<AgendarPageMentoriaComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AgendarPageMentoriaComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AgendarPageMentoriaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
