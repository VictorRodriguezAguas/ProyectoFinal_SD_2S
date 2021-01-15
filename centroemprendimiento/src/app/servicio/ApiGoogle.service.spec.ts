import { TestBed } from '@angular/core/testing';

import { ApiGoogleService } from './ApiGoogle.service';

describe('ApiGoogleService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: ApiGoogleService = TestBed.get(ApiGoogleService);
    expect(service).toBeTruthy();
  });
});
