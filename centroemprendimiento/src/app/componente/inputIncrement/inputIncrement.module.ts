import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { InputIncrementComponent } from './inputIncrement.component';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { ImageCropperModule } from 'ngx-image-cropper';

@NgModule({
  declarations: [InputIncrementComponent],
  imports: [
    CommonModule,
    SharedModule,
    ImageCropperModule
  ],
  exports:[InputIncrementComponent],
  providers: []
})
export class InputIncrementModule { }
