/* tslint:disable:no-unused-variable */
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { DebugElement } from '@angular/core';

import { MentorCalendarioComponent } from './mentor-calendario.component';

describe('MentorCalendarioComponent', () => {
  let component: MentorCalendarioComponent;
  let fixture: ComponentFixture<MentorCalendarioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MentorCalendarioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MentorCalendarioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
