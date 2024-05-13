import{m as c,a as d}from"./alertModal-064cb8ca.js";import{m as _}from"./mixins-00a35343.js";import{o as t,h as m,i as r,b as s,j as i,a as e,t as l,T as f}from"./index-a6b313c0.js";import{_ as h}from"./_plugin-vue_export-helper-c27b6911.js";const y={components:{modalconfirmacion:c,modalalerta:d},mixins:[_],data:()=>({}),props:{organization1:Object},created(){}},b={name:"modal"},z={class:"modal-mask"},u={class:"modal-wrapper"},g={class:"modal-container"},v={class:"modal-header pb-1",style:{"font-weight":"bold",color:"#444444"}},k=s("label",{class:"col-5 m-0",style:{"font-size":"17px"}},"Organization Details",-1),x={class:"modal-body"},p={class:"form-group"},w={class:"row"},D=s("div",{class:"text-uppercase pb-4"},"General information:",-1),E={class:"col-md-6 m-0 fs-8 pb-1"},B=s("label",null,"Status: ",-1),C={key:0,class:"fw-normal",style:{"font-size":"14px"}},N={key:1,class:"fw-normal",style:{"font-size":"14px"}},P={class:"col-md-6 m-0 fs-8 pb-1"},S=s("label",null,"Type Event: ",-1),R={key:0,class:"fw-normal",style:{"font-size":"14px"}},T={key:1,class:"fw-normal",style:{"font-size":"14px"}},j={class:"col-md-6 m-0 fs-8 pb-1"},O=s("label",null,"Event Name: ",-1),G={key:0,class:"fw-normal",style:{"font-size":"14px"}},V={key:1,class:"fw-normal",style:{"font-size":"14px"}},X={class:"col-md-6 m-0 fs-8 pb-1"},q=s("label",null,"Country: ",-1),A={key:0,class:"fw-normal",style:{"font-size":"14px"}},F={key:1,class:"fw-normal",style:{"font-size":"14px"}},H={class:"col-md-6 m-0 fs-8 pb-1"},I=s("label",null,"City: ",-1),J={key:0,class:"fw-normal",style:{"font-size":"14px"}},K={key:1,class:"fw-normal",style:{"font-size":"14px"}},L={class:"col-md-6 m-0 fs-8 pb-1"},M=s("label",null,"Start Date: ",-1),Q={key:0,class:"fw-normal",style:{"font-size":"14px"}},U={key:1,class:"fw-normal",style:{"font-size":"14px"}},W={class:"col-md-6 m-0 fs-8 pb-1"},Y=s("label",null,"Ending Date: ",-1),Z={key:0,class:"fw-normal",style:{"font-size":"14px"}},$={key:1,class:"fw-normal",style:{"font-size":"14px"}},ss={class:"col-md-6 m-0 fs-8 pb-1"},ts=s("label",null,"Number Participants: ",-1),os={key:0,class:"fw-normal",style:{"font-size":"14px"}},es={key:1,class:"fw-normal",style:{"font-size":"14px"}},ls={class:"col-md-6 m-0 fs-8 pb-1"},ns=s("label",null,"Comments: ",-1),as={key:0,class:"fw-normal",style:{"font-size":"14px"}},is={key:1,class:"fw-normal",style:{"font-size":"14px"}},cs={class:"col-md-6 m-0 fs-8 pb-1"},ds=s("label",null,"Progress Report: ",-1),_s={key:0,class:"fw-normal",style:{"font-size":"14px"}},ms={key:1,class:"fw-normal",style:{"font-size":"14px"}};function rs(n,a,o,fs,hs,ys){return t(),m(f,{name:"modalver"},{default:r(()=>[s("div",b,[s("div",z,[s("div",u,[s("div",g,[s("div",v,[i(n.$slots,"header",{},()=>[k]),s("a",{class:"btn btn-closed",onClick:a[0]||(a[0]=bs=>n.$emit("close")),ref:"closeBtn"},"X",512)]),s("div",x,[i(n.$slots,"body",{},()=>[s("div",p,[s("div",w,[D,s("div",E,[B,o.organization1.status!=null?(t(),e("label",C,l(o.organization1.status),1)):(t(),e("label",N,"---"))]),s("div",P,[S,o.organization1.typeEvent!=null?(t(),e("label",R,l(o.organization1.typeEvent),1)):(t(),e("label",T,"---"))]),s("div",j,[O,o.organization1.eventName!=null?(t(),e("label",G,l(o.organization1.eventName),1)):(t(),e("label",V,"---"))]),s("div",X,[q,o.organization1.country!=null?(t(),e("label",A,l(o.organization1.country),1)):(t(),e("label",F,"---"))]),s("div",H,[I,o.organization1.city!=null?(t(),e("label",J,l(o.organization1.city),1)):(t(),e("label",K,"---"))]),s("div",L,[M,o.organization1.startDate!=null?(t(),e("label",Q,l(o.organization1.startDate),1)):(t(),e("label",U,"---"))]),s("div",W,[Y,o.organization1.endingDate!=null?(t(),e("label",Z,l(o.organization1.endingDate),1)):(t(),e("label",$,"---"))]),s("div",ss,[ts,o.organization1.numberParticipants!=null?(t(),e("label",os,l(o.organization1.numberParticipants),1)):(t(),e("label",es,"---"))]),s("div",ls,[ns,o.organization1.comments!=null?(t(),e("label",as,l(o.organization1.comments),1)):(t(),e("label",is,"---"))]),s("div",cs,[ds,o.organization1.progressReport!=null?(t(),e("label",_s,l(o.organization1.progressReport),1)):(t(),e("label",ms,"---"))])])])])])])])])])]),_:3})}const ks=h(y,[["render",rs]]);export{ks as m};
