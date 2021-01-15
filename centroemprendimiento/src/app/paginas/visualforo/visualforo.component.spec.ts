import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { VisualforoComponent } from './visualforo.component';

describe('VisualforoComponent', () => {
  let component: VisualforoComponent;
  let fixture: ComponentFixture<VisualforoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ VisualforoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(VisualforoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
