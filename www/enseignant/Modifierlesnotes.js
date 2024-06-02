function createSection() {
    
    var section = document.createElement('ul');
    section.classList.add('baseSection');

    var sectionItem = document.createElement('li');

    var sectionName = prompt("Veuillez saisir un nom pour la matière :");
    if (sectionName !== null && sectionName.trim() !== "") {
    
        var arrow = document.createElement('span');
        arrow.textContent = '▶';
        arrow.classList.add('arrow');
        sectionItem.appendChild(arrow);

        var sectionNameSpan = document.createElement('span');
        sectionNameSpan.textContent = sectionName;
        sectionItem.appendChild(sectionNameSpan);

        var subSectionList = document.createElement('ul');
        subSectionList.classList.add('subSectionsList');
        sectionItem.appendChild(subSectionList);

        sectionItem.addEventListener('click', function() {
            toggleSubMenu(subSectionList, arrow);
        });

        var plusSign = document.createElement('span');
        plusSign.textContent = '+';
        plusSign.classList.add('plusSign');
        plusSign.addEventListener('click', function(event) {
            event.stopPropagation();
            createSubsection(subSectionList);
        });
        sectionItem.appendChild(plusSign);

        section.appendChild(sectionItem);
        document.getElementById('sectionsContainer').appendChild(section);
    }
}

function toggleSubMenu(subSectionList, arrow) {
    if (subSectionList.style.display === 'block') {
        subSectionList.style.display = 'none';
        arrow.textContent = '▶';
    } else {
        subSectionList.style.display = 'block';
        arrow.textContent = '▼';
    }
}


function createSubsection(parentSection) {

    var subsectionItem = document.createElement('li');
    subsectionItem.textContent = prompt("Veuillez saisir un nom pour le devoir :");
    subsectionItem.addEventListener('click', function() {
        displayDetails(subsectionItem.textContent);
    });
    parentSection.appendChild(subsectionItem);
}

function displayDetails(devoirName) {

    var detailsTable = document.getElementById('devoirDetailsTable').getElementsByTagName('tbody')[0];
    detailsTable.innerHTML = '';

    var detailsData = [
        { info: "Note", value: "" },
        { info: "Élève (Nom et Prénom)", value: "" },
    ];

    detailsData.forEach(function(detail) {
        var row = document.createElement('tr');
        var cell1 = document.createElement('td');
        cell1.textContent = detail.info;
        row.appendChild(cell1);
        var cell2 = document.createElement('td');
        if (detail.value) {
            var input = document.createElement('input');
            input.type = 'text';
            input.value = detail.value;
            cell2.appendChild(input);
        }
        row.appendChild(cell2);
        detailsTable.appendChild(row);
    });
}

document.getElementById('addSectionButton').addEventListener('click', createSection);
