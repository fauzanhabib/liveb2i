function Value(value, metadata){
    this.value= value;
    this.metadata = metadata;
}

Value.prototype.toString = function(){
    return this.value;
}




function radar_data (student_vrm, types) {

    var data = new Value(student_vrm.types.percent_to_goal, {
        tooltipx : student_vrm.types.raw_value
    })

    // var repeat = new Value(student_vrm.repeats.percent_to_goal, {
    //     tooltipx : student_vrm.repeats.raw_value
    // })

    // var mic = new Value(student_vrm.mic.percent_to_goal, {
    //     tooltipx : student_vrm.mic.raw_value
    // })

    // var headphone = new Value(student_vrm.headphone.percent_to_goal, {
    //     tooltipx : student_vrm.headphone.raw_value
    // })
    // var sr = new Value(student_vrm.sr.percent_to_goal, {
    //     tooltipx : student_vrm.sr.raw_value
    // })

    // var mt = new Value(student_vrm.mt.percent_to_goal, {
    //     tooltipx : student_vrm.mt.raw_value
    // })

}



var valueData = function(point){
    return point.value.metadata.tooltipx;
}


