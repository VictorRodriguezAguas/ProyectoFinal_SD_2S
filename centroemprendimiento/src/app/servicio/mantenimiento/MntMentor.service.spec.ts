/* tslint:disable:no-unused-variable */

import { TestBed, async, inject } from '@angular/core/testing';
import { MntMentorService } from './MntMentor.service';

describe('Service: MntMentor', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [MntMentorService]
    });
  });

  it('should ...', inject([MntMentorService], (service: MntMentorService) => {
    expect(service).toBeTruthy();
  }));
});
