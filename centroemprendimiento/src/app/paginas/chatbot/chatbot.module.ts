import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ChatbotComponent } from './chatbot.component';
import {ChatbotRoutingModule} from "./chatbot-routing.module";

import { NbThemeModule, NbLayoutModule, NbChatModule, NbSpinnerModule } from '@nebular/theme';
import { NbEvaIconsModule } from '@nebular/eva-icons';

@NgModule({
  imports: [
    CommonModule,
    ChatbotRoutingModule,
    NbThemeModule.forRoot({ name: 'default' }),
    NbLayoutModule,
    NbEvaIconsModule,
    NbChatModule,
    NbSpinnerModule
  ],
  declarations: [ChatbotComponent],
  exports: [ChatbotComponent]
})
export class ChatbotModule { }
