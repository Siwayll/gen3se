%skip       space       \s

%token  scenari                  Scenario  -> sce
%token  sce:spaceComa            [ \n\r]*,[ \n\r]*
%token  sce:tab                  [ ]{2}
%token  sce:space                [ ]
%token  sce:eol                  [\n\r]+
%token  sce:end                  [-]{3} -> default
%token  sce:choice               >
%token  sce:modCleat             mod:[ ]?
%token  sce:modName              [A-Z][A-za-z]*
%token  sce:name                 [a-zéêèâàôîïöäë][a-zA-ZéêèâàôîïöäëùÉÊÈÂÀÔÎÏÖÄËÙ]*
%token  sce:_string              "  -> string


%token  choice                   Choix -> chce
%token  chce:tab                 [ ]{2}
%token  chce:space               [ ]
%token  chce:eol                 [\n\r]+
%token  chce:tagCleat            # -> tag
%token  chce:globalCleat         \*
%token  chce:bracket_            \[
%token  chce:_bracket            \]
%token  chce:rBracket_           \(
%token  chce:_rBracket           \)
%token  chce:colon               :
%token  chce:null                ~
%token  chce:storageDelimiter    \.
%token  chce:end                 [-]{3} -> default
%token  chce:name                [a-zéêèâàôîïöäë][a-zA-ZéêèâàôîïöäëùÉÊÈÂÀÔÎÏÖÄËÙ]*
%token  chce:integer             (0|[1-9]\d*)
%token  chce:string              ([^"]+)
%token  chce:_string             "  -> string

%token  string:value             ([^"]+)
%token  string:string_           " -> __shift__


%token  tag:tagName             [A-Z_&!]+
%token  tag:rBracket_           \(
%token  tag:_rBracket           \) -> __shift__
%token  tag:tagWeight           [+-]?(0|[1-9]\d*)


#root:
    scenario()? choice()*

name:
    <name>

tagName:
    <tagName>

weight:
    <integer>

tagValue:
    <tagWeight>

null:
    <null>

stringQuote:
    ::_string:: <value>? ::string_::

//                                     _
//    _____________  ____  ____ ______(_)___
//   / ___/ ___/ _ \/ __ \/ __ `/ ___/ / __ \
//  (__  ) /__/  __/ / / / /_/ / /  / / /_/ /
// /____/\___/\___/_/ /_/\__,_/_/  /_/\____/
#scenario:
    ::scenari:: ::space:: name() ::eol::
    ( scenarioMod() )?
    ( scenarioChoice() )+
    ( scenarioRender() )?
    ::end::

#scenarioChoice:
    ::tab:: ::choice:: ::space:: name() ::eol::

#scenarioMod:
    ::tab:: ::modCleat:: ( <modName> ( ::spaceComa:: | ::eol:: ) )+

#scenarioRender:
    stringQuote() ::eol::



//         __          _
//   _____/ /_  ____  (_)_______
//  / ___/ __ \/ __ \/ / ___/ _ \
// / /__/ / / / /_/ / / /__/  __/
// \___/_/ /_/\____/_/\___/\___/
#choice:
  ::choice:: ::space:: name() choiceStorage()? ::eol::
  ( choiceGlobalElement() )*
  ( choiceOption() )+
  ::end::

choiceGlobalElement:
    ::tab:: ::tab:: ::globalCleat:: choiceElement() ::eol::

#choiceStorage:
    ::space:: ::bracket_:: name() ( ::storageDelimiter:: name())* ::_bracket::

#choiceOption:
    ::tab:: ( name() ::space:: )? ( weight() ::space:: )? choiceMainValue() ( ::space:: choiceTag() )*
    ( ::eol:: ::tab:: ::tab:: choiceElement() )*
    ::eol::

#choiceMainValue:
    (choiceDataName()  ::colon:: )? choiceDataValue()

#choiceElement:
    choiceData()

#choiceTag:
    ::tagCleat:: tagName() ::rBracket_:: tagValue() ::_rBracket::

choiceData:
    choiceDataName() ::colon:: choiceDataValue()

choiceDataName:
    stringQuote() | name()

choiceDataValue:
    stringQuote() | null()

