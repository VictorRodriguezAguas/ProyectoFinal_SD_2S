/* tslint:disable:no-unused-variable */
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { DebugElement } from '@angular/core';

import { MntHorarioComponent } from './mntHorario.component';

describe('MntHorarioComponent', () => {
  let component: MntHorarioComponent;
  let fixture: ComponentFixture<MntHorarioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MntHorarioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MntHorarioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
