/* tslint:disable:no-unused-variable */
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { DebugElement } from '@angular/core';

import { MntPeriodoComponent } from './mntPeriodo.component';

describe('MntPeriodoComponent', () => {
  let component: MntPeriodoComponent;
  let fixture: ComponentFixture<MntPeriodoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MntPeriodoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MntPeriodoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
