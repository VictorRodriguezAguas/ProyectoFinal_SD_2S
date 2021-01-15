import { TestBed } from '@angular/core/testing';

import { PanelforosService } from './panelforos.service';

describe('PanelforosService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: PanelforosService = TestBed.get(PanelforosService);
    expect(service).toBeTruthy();
  });
});
