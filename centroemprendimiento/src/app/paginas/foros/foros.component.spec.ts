import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ForosComponent } from './foros.component';

describe('ForosComponent', () => {
  let component: ForosComponent;
  let fixture: ComponentFixture<ForosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ForosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ForosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
