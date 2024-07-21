// Таймер
function timer() {
    setTimeout(step, interval);
}

// Каждую секунду увеличивает параметр секунды, энергии (если нужно)
function step() {
    let dt = Date.now() - expected;

    if (energy < energy_max) {

        second++;

        if (second === second_max) {
            second = 0;
            energy++;

            if (typeof checkCreatePostEnergy === 'function') {
                checkCreatePostEnergy();
            }

            if (typeof checkCreateCommentEnergy === 'function') {
                checkCreateCommentEnergy();
            }
        }

        viewEnergy();
        expected += interval;
        setTimeout(step, Math.max(0, interval - dt));
    }
}

/**
 * Обновляет значения энергии и длину полосок энергии
 */
function viewEnergy() {
    document.getElementById('energy').innerHTML = energy;
    document.getElementById('energy_max').innerHTML = energy_max;

    energy_bar = Math.round((energy/energy_max) * 100);
    document.getElementById('energy_bar_div').style.width = energy_bar + '%';

    second_bar = Math.round((second/second_max) * 100);
    document.getElementById('second_bar_div').style.width = second_bar + '%';
}

// При загрузке страницы запускаем таймер
document.addEventListener("DOMContentLoaded", timer);
