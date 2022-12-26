<div class="lu-widget {$rawObject->getid()}_page_container" >
    <div class="lu-widget__body">
        <div class="lu-row">
            <div class="lu-col-md-12">
            <div class="box light ">
                <div class="box-title pl-20 pr-20 pt-10 " style="background-color:#c7e9f4;border-bottom: 1px solid #edeff2;">
                    <div class="caption">
                        <span class="caption-subject bold font-red-thunderbird uppercase">
                            <h6><strong>{$MGLANG->absoluteT("MessageSystemSubject")}: {$rawObject->getSubject()} </strong></h6>
                        </span>
                    </div>
                </div>
                <div class="content pl-40 pr-40 pb-20 pt-20">
                    {$rawObject->getMessageContent()}
                </div>
                <div class="footer pl-40 pr-40 pt-20 pb-10" style="background-color:#c7e9f4;">
                    <div class="row">
                        <div class="col-lg-6 text-left">
                           {$rawObject->getMessageDate()}
                        </div>
                        <div class="col-lg-6 text-right">
                            {$MGLANG->absoluteT("MessageSystemFrom")}:  {$rawObject->getMessageType()}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

