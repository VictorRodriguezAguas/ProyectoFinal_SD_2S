/* tslint:disable:no-unused-variable */
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { DebugElement } from '@angular/core';

import { InputIncrementComponent } from './inputIncrement.component';

describe('InputIncrementComponent', () => {
  let component: InputIncrementComponent;
  let fixture: ComponentFixture<InputIncrementComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ InputIncrementComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(InputIncrementComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
