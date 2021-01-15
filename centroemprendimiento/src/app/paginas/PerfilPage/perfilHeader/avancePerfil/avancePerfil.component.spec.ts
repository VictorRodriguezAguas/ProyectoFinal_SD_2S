/* tslint:disable:no-unused-variable */
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { DebugElement } from '@angular/core';

import { AvancePerfilComponent } from './avancePerfil.component';

describe('AvancePerfilComponent', () => {
  let component: AvancePerfilComponent;
  let fixture: ComponentFixture<AvancePerfilComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AvancePerfilComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AvancePerfilComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
