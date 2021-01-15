import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { PerfilHeaderComponent } from './perfilHeader.component';
import { FotoPerfilModule } from 'src/app/componente/fotoPerfil/fotoPerfil.module';
import { NgbProgressbarModule } from '@ng-bootstrap/ng-bootstrap';
import {BarRatingModule} from 'ngx-bar-rating';

@NgModule({
  declarations: [PerfilHeaderComponent],
  imports: [
    CommonModule,
    SharedModule,
    FotoPerfilModule,
    NgbProgressbarModule,
    BarRatingModule
  ],
  exports:[PerfilHeaderComponent]
})
export class PerfilHeaderModule { }
