/**
 * Created by sahaque on 4/19/2017.
 */

describe('FamilyLink', function() {
    before(function () {
        this.familyLink = new FamilyLink();
    });
    describe('ParseTree', function() {
        it('testParseFamC', function () {
            var tree = [['1 FAMC @F191@']];
            this.familyLink.parseTree(tree, '5.5.1', 'FAMC');
            expect(this.familyLink.toString()).to.equal("(FamilyId->F191, Notes->())");
        });

        it('testParseFamS', function () {
            var tree = [['1 FAMS @F191@']];
            this.familyLink.parseTree(tree, '5.5.1');
            expect(this.familyLink.toString()).to.equal("(FamilyId->F191, Notes->())");
        });

        it('testParseFullFamC', function() {
            "use strict";
            this.familyLink = new FamilyLink();
            var tree = [['1 FAMC @F191@', [
                ['2 NOTE van Gogh Rocks!'],
                ['2 PEDI foster'],
                ['2 STAT proven']]
            ]];
            this.familyLink.parseTree(tree, '5.5.1','FAMC');
            expect(this.familyLink.toString()).to.equal("(FamilyId->F191, LinkageType->foster, LinkageStatus->proven, Notes->(\n(Version->5.5.1, Text->van Gogh Rocks!)))");
        });

        it('testParseFullFamS', function() {
            "use strict";
            this.familyLink = new FamilyLink();
            var tree = [['1 FAMS @F191@', [
                ['2 NOTE van Gogh Rocks!']]
            ]];
            this.familyLink.parseTree(tree, '5.5.1');
            expect(this.familyLink.toString()).to.equal("(FamilyId->F191, Notes->(\n(Version->5.5.1, Text->van Gogh Rocks!)))");
        });
    });
});