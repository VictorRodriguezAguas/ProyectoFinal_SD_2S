import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { AlertModule, BreadcrumbModule, CardModule, ModalModule } from './components';
import { DataFilterPipe } from './components/data-table/data-filter.pipe';
import { TodoListRemoveDirective } from './components/todo/todo-list-remove.directive';
import { TodoCardCompleteDirective } from './components/todo/todo-card-complete.directive';
import { PERFECT_SCROLLBAR_CONFIG, PerfectScrollbarConfigInterface, PerfectScrollbarModule } from 'ngx-perfect-scrollbar';
import { ClickOutsideModule } from 'ng-click-outside';

import { SpinnerComponent } from './components/spinner/spinner.component';
import { ApexChartComponent } from './components/chart/apex-chart/apex-chart.component';
import {ApexChartService} from './components/chart/apex-chart/apex-chart.service';
import { ToastComponent } from './components/toast/toast.component';
import {ToastService} from './components/toast/toast.service';
import { GalleryComponent } from './components/gallery/gallery.component';
import {LightboxModule} from 'ngx-lightbox';
import { UppercaseDirective } from 'src/app/directivas/uppercase.directive';
import { LowerCaseInputDirective } from 'src/app/directivas/LowerCaseInput.directive';
import { msmValidadorDirective, msmValidadorDirective2, validarEdadMinDirective } from 'src/app/validators/validadores';
import { Redes_socialesModule} from 'src/app/componente/perfil/redes_sociales/redes_sociales.module';
/*import 'hammerjs';
import 'mousetrap';
import { GalleryModule } from '@ks89/angular-modal-gallery';*/

const DEFAULT_PERFECT_SCROLLBAR_CONFIG: PerfectScrollbarConfigInterface = {
  suppressScrollX: true
};

@NgModule({
  imports: [
    CommonModule,
    PerfectScrollbarModule,
    FormsModule,
    ReactiveFormsModule,
    AlertModule,
    CardModule,
    BreadcrumbModule,
    ModalModule,
    ClickOutsideModule,
    LightboxModule,
    Redes_socialesModule
  ],
  exports: [
    CommonModule,
    PerfectScrollbarModule,
    FormsModule,
    ReactiveFormsModule,
    AlertModule,
    CardModule,
    BreadcrumbModule,
    ModalModule,
    Redes_socialesModule,
    DataFilterPipe,
    TodoListRemoveDirective,
    TodoCardCompleteDirective,
    ClickOutsideModule,
    SpinnerComponent,
    ApexChartComponent,
    GalleryComponent,
    ToastComponent,
    LowerCaseInputDirective,
    UppercaseDirective,
    validarEdadMinDirective,
    msmValidadorDirective,
    msmValidadorDirective2
  ],
  declarations: [
    DataFilterPipe,
    TodoListRemoveDirective,
    TodoCardCompleteDirective,
    SpinnerComponent,
    ApexChartComponent,
    ToastComponent,
    GalleryComponent,
    UppercaseDirective,
    LowerCaseInputDirective,
    validarEdadMinDirective,
    msmValidadorDirective,
    msmValidadorDirective2
  ],
  providers: [
    {
      provide: PERFECT_SCROLLBAR_CONFIG,
      useValue: DEFAULT_PERFECT_SCROLLBAR_CONFIG
    },
    ApexChartService,
    ToastService
  ]
})
export class SharedModule { }
