/* tslint:disable:no-unused-variable */
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { DebugElement } from '@angular/core';

import { AgendarPruebaComponent } from './agendarPrueba.component';

describe('AgendarPruebaComponent', () => {
  let component: AgendarPruebaComponent;
  let fixture: ComponentFixture<AgendarPruebaComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AgendarPruebaComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AgendarPruebaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
