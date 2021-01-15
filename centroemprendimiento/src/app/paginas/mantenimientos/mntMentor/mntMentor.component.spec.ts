/* tslint:disable:no-unused-variable */
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { DebugElement } from '@angular/core';

import { MntMentorComponent } from './mntMentor.component';

describe('MntMentorComponent', () => {
  let component: MntMentorComponent;
  let fixture: ComponentFixture<MntMentorComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MntMentorComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MntMentorComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
