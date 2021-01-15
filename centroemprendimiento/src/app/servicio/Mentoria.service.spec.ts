/* tslint:disable:no-unused-variable */

import { TestBed, async, inject } from '@angular/core/testing';
import { MentoriaService } from './Mentoria.service';

describe('Service: Mentoria', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [MentoriaService]
    });
  });

  it('should ...', inject([MentoriaService], (service: MentoriaService) => {
    expect(service).toBeTruthy();
  }));
});
