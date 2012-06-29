function ProgramSession (id, nativeId, program, startDate, startTime, description, instructors, nonSchoolSite, schoolSite, courseManager, notes, userManager)
{
    this.Id = id;
    this.NativeId = nativeId ? nativeId : 0;
    this.Program = program;
    this.StartDate = startDate;
    this.StartTime = startTime;
    this.Description = description;
    this.Instructors = instructors;
    this.NonSchoolSite = nonSchoolSite;
    this.SchoolSite = schoolSite;
    this.CourseManager = courseManager;
    this.UserManager = userManager;
    this.Notes = notes;
    
    this.GetLocation = function(){
        if (this.SchoolSite && this.SchoolSite.Id){
            return this.SchoolSite.Name;
        }
        else if (this.NonSchoolSite && this.NonSchoolSite.Id){
            return this.NonSchoolSite.Name;
        }
        else{
            return "";
        }
    }
    
    this.GetCourseManagerName = function(){
        if (this.CourseManager && this.CourseManager.Id){
            return this.CourseManager.Name;
        } else if (this.UserManager && this.UserManager.Id) {
            return this.UserManager.Name; 
        }
        else{
            return "";
        }
    }
    
    this.GetHiddenInput = function(){
        var result = [];
        result.push("<input type='hidden' value='" + this.NativeId + "' name='sessionList[" + this.Id + "][ID]'" + " />");
        result.push("<input type='hidden' value='" + this.getNullableValue(this.StartDate) + "' name='sessionList[" + this.Id + "][StartDate]'" + " />");
        result.push("<input type='hidden' value='" + this.getNullableValue(this.StartTime) + "' name='sessionList[" + this.Id + "][StartTime]'" + " />");
        result.push("<input type='hidden' value='" + this.getNullableValue(this.Description) + "' name='sessionList[" + this.Id + "][Description]'" + " />");
        result.push("<input type='hidden' value='" + this.getNullableValue(this.Instructors) + "' name='sessionList[" + this.Id + "][Instructors]'" + " />");
        result.push("<input type='hidden' value='" + this.getNullableValue(this.Notes) + "' name='sessionList[" + this.Id + "][Notes]'" + " />");
        result.push("<input type='hidden' value='" + (this.NonSchoolSite ? this.getNullableValue(this.NonSchoolSite.Id) : '')
                    + "' name='sessionList[" + this.Id + "][NonSchoolSite]'" + " />");
        result.push("<input type='hidden' value='" + (this.SchoolSite ? this.getNullableValue(this.SchoolSite.Id) : '')
                    + "' name='sessionList[" + this.Id + "][SchoolSite]'" + " />");
        result.push("<input type='hidden' value='" + (this.CourseManager ? this.getNullableValue(this.CourseManager.Id) : '')
                    + "' name='sessionList[" + this.Id + "][CourseManager]'" + " />");
        result.push("<input type='hidden' value='" + (this.UserManager ? this.getNullableValue(this.UserManager.Id) : '')
                    + "' name='sessionList[" + this.Id + "][UserManager]'" + " />");                
        return result;
    }
    
    this.getNullableValue = function(value){
        if (!value || value == ''){
            return '';
        }
        return value;
    }
}


