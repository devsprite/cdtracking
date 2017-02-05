$(document).ready(function () {

    var hoverBackgroundColor = [
        "rgba(229, 47, 35, 0.9)",
        "rgba(211, 84, 0, 0.9)",
        "rgba(243, 156, 18, 0.9)",
        "rgba(241, 196, 15, 0.9)",
        "rgba(39, 174, 96, 0.9)",
        "rgba(41, 128, 185, 0.9)",
        "rgba(142, 68, 173, 0.9)",
        "rgba(44, 62, 80, 0.9)"
    ];

    var backgroundColor = [
        "rgba(229, 47, 35, 1)",
        "rgba(211, 84, 0, 1)",
        "rgba(243, 156, 18, 1)",
        "rgba(241, 196, 15, 1)",
        "rgba(39, 174, 96, 1)",
        "rgba(41, 128, 185, 1)",
        "rgba(142, 68, 173, 1)",
        "rgba(44, 62, 80, 1)"
    ];

    var dataCountTracking = {
        labels: countTrackingBetweenDateJsonHeader,
        datasets: [
            {
                label: 'Tracking',
                data: countTrackingBetweenDateJsonValue,
                backgroundColor: backgroundColor,
                hoverBackgroundColor: hoverBackgroundColor
            }]
    };

    var countTracking = document.getElementById("countTrackingBetweenDate");
    var countTrackingChart = new Chart(countTracking,{
        type: 'bar',
        data: dataCountTracking,
        animation:{
            animateScale:true
        },
        options: {
            scales: {
                xAxes: [{
                    stacked: true
                }]
            }
        }
    });


    var trackingProspects = {
        labels: trackingProspectsHeader,
        datasets: [
            {
                label: 'Commande',
                data: trackingProspectsValues,
                backgroundColor: backgroundColor,
                hoverBackgroundColor: hoverBackgroundColor,
                borderWidth: 1
            }]
    };

    var tracking = document.getElementById("trackingProspects");
    var trackingProspectsChart = new Chart(tracking,{
        type: 'bar',
        data: trackingProspects,
        animation:{
            animateScale:true
        },
        options: {
            scales: {
                yAxes: [{
                    stacked: true
                }]
            }
        }
    });

});