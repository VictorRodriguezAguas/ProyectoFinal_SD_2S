import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ForosComponent } from './foros.component';
import {ForosRoutingModule} from "./foros-routing.module";

import {SharedModule} from 'src/app/theme/shared/shared.module';
import {TinymceModule} from 'angular2-tinymce';
import {DataTablesModule} from 'angular-datatables';
import {FormsModule} from '@angular/forms';
import {TagInputModule} from 'ngx-chips';
@NgModule({
  declarations: [ForosComponent],
  imports: [
    CommonModule,
    ForosRoutingModule,
    SharedModule,
    FormsModule,
    DataTablesModule,
    TagInputModule,
    TinymceModule

  ],
  exports:[ForosComponent]
})
export class ForosModule { }
