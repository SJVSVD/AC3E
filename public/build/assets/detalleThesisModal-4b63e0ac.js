import{m as r,a as _}from"./alertModal-064cb8ca.js";import{m as h}from"./mixins-00a35343.js";import{o as l,h as m,i as u,b as s,j as d,g as e,t as o,a,f as n,T as f}from"./index-a6b313c0.js";import{_ as b}from"./_plugin-vue_export-helper-c27b6911.js";const y={components:{modalconfirmacion:r,modalalerta:_},mixins:[h],data:()=>({}),props:{thesisStudent1:Object},created(){}},S={name:"modal"},x={class:"modal-mask"},w={class:"modal-wrapper"},z={class:"modal-container"},p={class:"modal-header pb-1",style:{"font-weight":"bold",color:"#444444"}},N=s("label",{class:"col-5 m-0",style:{"font-size":"17px"}},"Thesis Details:",-1),v={class:"col-5 m-0 fs-8 pt-2"},k=s("label",{class:"fw-normal",style:{"font-size":"14px"}},null,-1),T={class:"modal-body"},g={class:"form-group"},C={class:"row"},D=s("div",{class:"text-uppercase pb-2"},"Personal information:",-1),I={class:"col-4 m-0 fs-8 pb-1"},P={class:"fw-normal",style:{"font-size":"14px"}},B={class:"col-4 m-0 fs-8 pb-1"},O={class:"fw-normal",style:{"font-size":"14px"}},A={class:"col-4 m-0 fs-8 pb-1"},E={class:"fw-normal",style:{"font-size":"14px"}},V={class:"col-4 m-0 fs-8 pb-2"},j={class:"fw-normal",style:{"font-size":"14px"}},M=s("hr",{size:"4",class:"separador"},null,-1),R=s("div",{class:"text-uppercase pb-2"},"Thesis information:",-1),U={class:"col-6 m-0 fs-8 pb-1"},Y={class:"fw-normal",style:{"font-size":"14px"}},G={class:"col-6 m-0 fs-8 pb-1"},X={class:"fw-normal",style:{"font-size":"14px"}},q={class:"col-6 m-0 fs-8 pb-1"},F={class:"fw-normal",style:{"font-size":"14px"}},H={class:"col-6 m-0 fs-8 pb-2"},J={class:"fw-normal",style:{"font-size":"14px"}},K={key:0,size:"4",class:"separador"},L={key:1,class:"text-uppercase pb-2"},Q={key:2,class:"col-6 m-0 fs-8 pb-1"},W={class:"fw-normal",style:{"font-size":"14px"}},Z={key:3,class:"col-6 m-0 fs-8 pb-2"},$={class:"fw-normal",style:{"font-size":"14px"}},ss={key:4,size:"4",class:"separador"},ts={key:5,class:"text-uppercase pb-2"},es={key:6,class:"col-6 m-0 fs-8 pb-1"},os={class:"fw-normal",style:{"font-size":"14px"}},ls={key:7,class:"col-6 m-0 fs-8 pb-2"},as={class:"fw-normal",style:{"font-size":"14px"}},ns={key:8,size:"4",class:"separador"},is={key:9,class:"text-uppercase pb-2"},cs={key:10,class:"col-6 m-0 fs-8 pb-1"},ds={class:"fw-normal",style:{"font-size":"14px"}},rs={key:11,class:"col-6 m-0 fs-8 pb-2"},_s={class:"fw-normal",style:{"font-size":"14px"}},hs=s("hr",{size:"4",class:"separador"},null,-1),ms=s("div",{class:"text-uppercase pb-2"},"Extra information:",-1),us={class:"col-4 m-0 fs-8 pb-1"},fs={class:"fw-normal",style:{"font-size":"14px"}},bs={class:"col-4 m-0 fs-8 pb-1"},ys={class:"fw-normal",style:{"font-size":"14px"}},Ss={class:"col-4 m-0 fs-8 pb-1"},xs={class:"fw-normal",style:{"font-size":"14px"}},ws={class:"col-6 m-0 fs-8 pb-1"},zs={class:"fw-normal",style:{"font-size":"14px"}},ps={class:"col-6 m-0 fs-8 pb-1"},Ns={class:"fw-normal",style:{"font-size":"14px"}},vs={class:"col-6 m-0 fs-8 pb-1"},ks={class:"fw-normal",style:{"font-size":"14px"}},Ts={class:"col-6 m-0 fs-8 pb-2"},gs={class:"fw-normal",style:{"font-size":"14px"}};function Cs(i,c,t,Ds,Is,Ps){return l(),m(f,{name:"modalver"},{default:u(()=>[s("div",S,[s("div",x,[s("div",w,[s("div",z,[s("div",p,[d(i.$slots,"header",{},()=>[N,s("label",v,[e(" User: "),k,e(o(t.thesisStudent1.usuario.name),1)])]),s("a",{class:"btn btn-closed",onClick:c[0]||(c[0]=Bs=>i.$emit("close")),ref:"closeBtn"},"X",512)]),s("div",T,[d(i.$slots,"body",{},()=>[s("div",g,[s("div",C,[D,s("label",I,[e(" Student Name: "),s("label",P,o(t.thesisStudent1.studentName||"---"),1)]),s("label",B,[e(" Run or Passport: "),s("label",O,o(t.thesisStudent1.runOrPassport||"---"),1)]),s("label",A,[e(" Gender: "),s("label",E,o(t.thesisStudent1.gender||"---"),1)]),s("label",V,[e(" Student Mail: "),s("label",j,o(t.thesisStudent1.studentMail||"---"),1)]),M,R,s("label",U,[e(" Thesis Status: "),s("label",Y,o(t.thesisStudent1.thesisStatus||"---"),1)]),s("label",G,[e(" Thesis Title: "),s("label",X,o(t.thesisStudent1.thesisTitle||"---"),1)]),s("label",q,[e(" Academic Degree: "),s("label",F,o(t.thesisStudent1.academicDegree||"---"),1)]),s("label",H,[e(" Degree Denomination: "),s("label",J,o(t.thesisStudent1.degreeDenomination||"---"),1)]),t.thesisStudent1.tutorName!=null?(l(),a("hr",K)):n("",!0),t.thesisStudent1.tutorName!=null?(l(),a("div",L,"Tutor information:")):n("",!0),t.thesisStudent1.tutorName!=null?(l(),a("label",Q,[e(" Tutor Name: "),s("label",W,o(t.thesisStudent1.tutorName||"---"),1)])):n("",!0),t.thesisStudent1.tutorName!=null?(l(),a("label",Z,[e(" Tutor Institution: "),s("label",$,o(t.thesisStudent1.tutorInstitution||"---"),1)])):n("",!0),t.thesisStudent1.cotutorName!=null?(l(),a("hr",ss)):n("",!0),t.thesisStudent1.cotutorName!=null?(l(),a("div",ts,"Cotutor information:")):n("",!0),t.thesisStudent1.cotutorName!=null?(l(),a("label",es,[e(" Cotutor Name: "),s("label",os,o(t.thesisStudent1.cotutorName||"---"),1)])):n("",!0),t.thesisStudent1.cotutorName!=null?(l(),a("label",ls,[e(" Cotutor Institution: "),s("label",as,o(t.thesisStudent1.cotutorInstitution||"---"),1)])):n("",!0),t.thesisStudent1.otherName!=null?(l(),a("hr",ns)):n("",!0),t.thesisStudent1.otherName!=null?(l(),a("div",is,"Other information:")):n("",!0),t.thesisStudent1.otherName!=null?(l(),a("label",cs,[e(" Other Name: "),s("label",ds,o(t.thesisStudent1.otherName||"---"),1)])):n("",!0),t.thesisStudent1.otherName!=null?(l(),a("label",rs,[e(" Other Institution: "),s("label",_s,o(t.thesisStudent1.otherInstitution||"---"),1)])):n("",!0),hs,ms,s("label",us,[e(" University: "),s("label",fs,o(t.thesisStudent1.university||"---"),1)]),s("label",bs,[e(" Year in which student starts program: "),s("label",ys,o(t.thesisStudent1.yearStart||"---"),1)]),s("label",Ss,[e(" Year in which the thesis ends: "),s("label",xs,o(t.thesisStudent1.yearThesisEnd||"---"),1)]),s("label",ws,[e(" Resources provided by the Center: "),s("label",zs,o(t.thesisStudent1.resourcesCenter||"---"),1)]),s("label",ps,[e(" Posterior working area: "),s("label",Ns,o(t.thesisStudent1.posteriorArea||"---"),1)]),s("label",vs,[e(" Institution of Posterior working area: "),s("label",ks,o(t.thesisStudent1.institutionPosteriorArea||"---"),1)]),s("label",Ts,[e(" Comments: "),s("label",gs,o(t.thesisStudent1.comments||"---"),1)])])])])])])])])])]),_:3})}const js=b(y,[["render",Cs]]);export{js as m};
