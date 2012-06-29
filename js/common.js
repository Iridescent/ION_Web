function IndexOfById(collection, id){
    result = -1;
    for (var i=0; i<collection.length; i++){
        if (collection[i].Id == id){
            result = i;
            break;
        }
    }
    return result;
}

String.prototype.isValidDate = function() {
    var match = this.match(/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/);
    if (match){
        var test = new Date(match[3], match[1] - 1, match[2]);
        return (
            (test.getMonth() == match[1] - 1) &&
            (test.getDate() == match[2]) &&
            (test.getFullYear() == match[3])
        );
    }
    return false;
}

function IsObjectNotEmpty(obj){
    if (obj){
        for (var i in obj) {
            if (obj.hasOwnProperty(i)) return true;
        }
    }
    return false;
}

Function.prototype.inheritsFrom = function(superClass) {
    var Inheritance = function(){};
    Inheritance.prototype = superClass.prototype;

    this.prototype = new Inheritance();
    this.prototype.constructor = this;
    this.prototype.superClass = superClass;
    this.superClass = superClass;
}
