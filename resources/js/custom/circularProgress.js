class CircularProgress {
    
    constructor(element, value) {
        this.element = $(element);
        this.render();
        this.setValue(value);
    }

    render() {
        this.uniqueID = this.generateUniqueID();
        $(this.element).replaceWith(`
        <div class="circular-progress" id="circular-progress-${this.uniqueID}">
            <div class="value-container">0%</div>
        </div>`);

        this.progressBar = $(`#circular-progress-${this.uniqueID}`);

    }

    generateUniqueID() {
        return Date.now().toString(36) + Math.random().toString(36).substring(2);
    }

    setValue(value) {

        let progressBar = $(this.progressBar);
        let valueContainer = $(this.progressBar).children().first();

        let progressValue = 0;
        let progressEndValue = value;
        let speed = 50;

        if(progressEndValue > 40)
            speed = 40;

        if(progressEndValue > 70)
            speed = 30;

        if(progressEndValue > 90)
            speed = 20;

        if(progressEndValue <= 0)
            return $(valueContainer).html(`0%`);

        let progress = setInterval(() => {
            progressValue++;
            $(valueContainer).html(`${progressValue}%`);
            $(progressBar).css(`background`, `conic-gradient(
                #1784D5 ${progressValue * 3.6}deg,
                #D9D9EF ${progressValue * 3.6}deg
            )`);
            if (progressValue >= progressEndValue) {
                clearInterval(progress);
            }
        }, speed);
    }

}

export { CircularProgress };